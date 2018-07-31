<?php
namespace App\Http\Controllers\Front;

use App\Entities\Parts\Phones;
use App\Service\Telegram;
use App\Service\Crm\Client;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PartsController extends Controller
{
    public function index($token, Phones $repository, Request $request)
    {
        if ($token !== env('API_TOKEN')) {
            throw new NotFoundHttpException();
        }

        try {
            $entity = $repository->create($request->only(["number", "source", "comment"]));
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return $entity->id;
    }

    public function test(Client $client)
    {
        $data = [
            "number" => "9134458745",
            "source" => "Тестик",
            "comment" => "Хороший заказчик"
        ];


        return $client->getRequest($data);
    }
}
