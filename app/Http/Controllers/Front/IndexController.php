<?php

namespace App\Http\Controllers\Front;

use App\Repositories\BitrixRepository;
use App\Repositories\PhonesRepository;
use App\Service\Bitrix;
use App\Service\TemplateBuilder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

class IndexController extends Controller
{
    public function index(TemplateBuilder $builder)
    {
        $data = $builder->init()->get();
        $city = !empty(env('APP_CITY')) ? 'в ' . env('APP_CITY') : '';

        return view(
            'front.index',
            [
                'data' => $data,
                'city' => $city
            ]
        );
    }

    public function products(TemplateBuilder $builder)
    {
        $data = $builder->init()->get();
        $city = !empty(env('APP_CITY')) ? 'в ' . env('APP_CITY') : '';

        return view(
            'front.products',
            [
                'data' => $data,
                'city' => $city
            ]
        );
    }

    public function call(TemplateBuilder $builder)
    {
        $data = $builder->init()->get();
        $city = !empty(env('APP_CITY')) ? 'в ' . env('APP_CITY') : '';

        return view(
            'front.call',
            [
                'data' => $data,
                'city' => $city
            ]
        );
    }

    public function form(Request $request, TemplateBuilder $builder)
    {
        $name = $request->get('name');
        $phone = $request->get('phone');
        $message = trim($request->get('message'));

        $this->sendMail($name, $phone, $message, $request);

        return redirect()->route('front_success');
    }

    public function success(TemplateBuilder $builder)
    {
        //
        $data = $builder->init()->get();

        return view('front.callback', ['data' => $data]);

    }

    public function mail(Request $request,
                         TemplateBuilder $builder,
                         PhonesRepository $phonesRepository,
                         Bitrix $service,
                         BitrixRepository $bitrixRepository)
    {
        $name = $request->get('name');
        $phone = $request->get('phone');
        $message = trim($request->get('message'));

        $request->session()->regenerateToken();

        $name = mb_substr($name, 0, 255);
        $phone = preg_replace('/[^0-9]/', '', $phone);
        $message = !empty($message) ? $message : null;

        if (!empty($name) && strlen($phone) > 4) {
            // add to base
            $phonesRepository->add(
                [
                    'ip' => $ip = !empty($_SERVER["HTTP_X_REAL_IP"]) ? $_SERVER["HTTP_X_REAL_IP"] : $request->ip(),
                    'city' => !empty(env('APP_CITY')) ? env('APP_CITY') : 'Не распознан',
                    'name' => $name,
                    'phone' => $phone,
                    'message' => $message
                ]
            );

            $this->sendMail($name, $phone, $message, $request);

            // add to bitrix
            $bitrixEntity = $bitrixRepository->get();

            $status = $service->init($bitrixEntity->getAccessToken(), $bitrixEntity->getRefreshToken());

            if ($status !== null) {
                $bitrixRepository->refresh($status["access_token"], $status["refresh_token"]);
            }

            $service->addContact($request->getHost(), $name, $phone, $message);
            $service->addLead($request->getHost(), $name, $phone, $message);
        }

        //
        $data = $builder->init()->get();

        return view('front.callback', ['data' => $data]);
    }

    /**
     * @param string $name
     * @param string $phone
     * @param $request
     */
    protected function sendMail($name, $phone, $message, $request)
    {
        $body = sprintf(
            "Заявка на просчёт\n\tИмя: %s\n\tТелефон: %s\n\tСообщение: %s",
            $name,
            $phone,
            !empty($message) ? $message : '[ нет сообщения ]'
        );

        Mail::raw($body, function (Message $message) use ($request) {

            $subject = '[' . $request->getHost() . '] Заявка с сайта';

            $message->to(env('MAIL_TO'));
            $message->subject($subject);
        });
    }
}
