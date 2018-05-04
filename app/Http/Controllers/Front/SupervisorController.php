<?php
namespace App\Http\Controllers\Front;

use App\Entities\Process\Copy;
use Illuminate\Routing\Controller;

class SupervisorController extends Controller
{
    public function index(Copy $copyRepository)
    {
        $list = $copyRepository->where('process_id', 2)->orderBy('id', 'desc')->get();
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

    public function sale(Copy $copyRepository)
    {
        $list = $copyRepository->where('process_id', 4)->orderBy('id', 'desc')->get();
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
