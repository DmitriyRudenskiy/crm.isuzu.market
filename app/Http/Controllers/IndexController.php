<?php
namespace App\Http\Controllers;

use App\Entities\Tasks;
use App\Repositories\PhonesRepository;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IndexController extends Controller
{
    public function index(PhonesRepository $repository)
    {
        $list = $repository->getNew();

        return view(
            'front.index.index',
            [
                'list' => $list
            ]
        );
    }

    public function show($userId, Tasks $repository)
    {
        if ($userId != "v1" && $userId != "s2") {
            throw new NotFoundHttpException();
        }

        $query = $repository->orderBy('period', 'asc')->where("is_ready", false);


        if ($userId == "v1") {
            $query->where("worker", "Вячеслав");
        }

        if ($userId == "s2") {
            $query->where("worker", "Сергей");
        }

        $list = $query->paginate();

        return view(
            'front.task.show',
            [
                'list' => $list
            ]
        );
    }
}
