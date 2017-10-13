<?php

namespace App\Http\Controllers\Front;

use App\Repositories\BitrixRepository;
use App\Service\Bitrix;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class BitrixController extends Controller
{
    public function index(Request $request, Bitrix $service, BitrixRepository $repository)
    {
        $code = $request->get('code');
        $url = !empty($code) ? $this->getUrlFromCode($code) : null;
        $model = $repository->get();

        $status = null;

        try {
            $status = $service->init($model->getAccessToken(), $model->getRefreshToken());
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }

        var_dump($service->info());

        if ($status !== null) {
            $repository->refresh($status["access_token"], $status["refresh_token"]);
            $model = $repository->get();
        }

        return view(
            'front.bitrix',
            [
                'code' => $code,
                'client_id' => env('BITRIX_CLIENT_ID'),
                'url' => $url,
                'model' => $model
            ]
        );
    }

    public function update(Request $request, BitrixRepository $repository)
    {
        $accessToken = trim($request->get('access_token'));
        $refreshToken = trim($request->get('refresh_token'));

        $repository->refresh($accessToken, $refreshToken);

        return redirect()->route('bitrix_code');
    }

    protected function getUrlFromCode($code)
    {
        $params = [
            "grant_type" => "authorization_code",
            "scope" => "crm,user",
            "code" => $code,
            "client_id" => env('BITRIX_CLIENT_ID'),
            "client_secret" => env('BITRIX_CLIENT_SECRET'),
            "redirect_uri" => "http://isuzu.market/bitrix/code"

        ];

        return sprintf(
            "https://%s/oauth/token/?%s",
            env('BITRIX_DOMAIN'),
            http_build_query($params)
        );
    }
}