<?php
namespace App\Http\Controllers\Admin;

use App\Repositories\PhonesRepository;
use App\Repositories\RegionsRepository;
use App\Service\GetRegionApi;
use App\Service\Phone;
use App\Service\Telegram;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    public function region(RegionsRepository $repository)
    {
        $list = $repository->getCountPhones();

        $categories = array_map(function ($row) {
            return $row->name;
        }, $list);

        $value = array_map(function ($row) {
            return (int)$row->phones;
        }, $list);

        //dd([$categories, $value]);

        return view(
            'admin.info.region',
            [
                'categories' => $categories,
                'value' => $value
            ]
        );
    }

    public function day(PhonesRepository $repository)
    {
        $list = $repository->getPhonesInDay();

        $categories = [];
        $items = [];

        foreach ($list as $value) {
            $date = $date = \DateTime::createFromFormat('Y-m-d', $value->day);

            $categories[] = $date->format('d.m');
            $items[] = (int)$value->phones;
        }

        return view(
            'admin.info.days',
            [
                'categories' => $categories,
                'value' => $items
            ]
        );
    }
}
