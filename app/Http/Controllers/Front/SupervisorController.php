<?php
namespace App\Http\Controllers\Front;

use App\Entities\Process\Copy;
use App\Entities\Process\Process;
use Illuminate\Routing\Controller;

class SupervisorController extends Controller
{
    public function index(Process $processRepository, Copy $copyRepository)
    {
        $list = $copyRepository->all();

        return view(
            'front.supervisor.index',
            [
                'list' => $list
            ]
        );
    }
}
