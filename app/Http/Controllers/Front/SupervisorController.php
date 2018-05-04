<?php
namespace App\Http\Controllers\Front;

use App\Entities\Process\Copy;
use App\Entities\Process\Process;
use Illuminate\Routing\Controller;

class SupervisorController extends Controller
{
    public function index(Process $processRepository, Copy $copyRepository)
    {
        $list = $copyRepository->orderBy('id', 'desc')->get();
        $calendar = [];

        foreach ($list as $copy) {
            $calendar[] = [
                "title" => $copy->name,
                "start" => $copy->created_at->format('Y-m-d H:i:s')
            ];
        }

        return view(
            "front.supervisor.index",
            [
                "list" => $list,
                "calendar" => $calendar
            ]
        );
    }
}
