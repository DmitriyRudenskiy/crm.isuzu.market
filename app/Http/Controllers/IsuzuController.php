<?php
namespace App\Http\Controllers;

use App\Entities\Parts\Phones;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entities\Process\Copy;
use App\Entities\Process\Workers;

class IsuzuController extends Controller
{
    public function index($token, Phones $repository)
    {
        if ($token !== env('VIEW_TOKEN')) {
            throw new NotFoundHttpException();
        }

        $list = $repository->where('worker_id', '>', 0)
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view(
            'front.isuzu.index',
            [
                'token' => $token,
                'list' => $list
            ]
        );
    }

    public function view($token, $processId, Workers $workersRepository, Copy $copyRepository)
    {
        if ($token !== env('VIEW_TOKEN')) {
            throw new NotFoundHttpException();
        }

        $copy = $copyRepository->findOrFail($processId);
        $process = $copy->process()->first();
        $user = $workersRepository->findOrFail($process->worker_id);

        return view(
            'front.isuzu.view',
            [
                'token' => $token,
                'user' => $user,
                'process' => $process,
                'copy' => $copy
            ]
        );
    }
}
