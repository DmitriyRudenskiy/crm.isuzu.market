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

        dd($list);

        $categories = array_map(function ($row) {
            return $row->name;
        }, $list);


        $value = array_map(function ($row) {
            return $row->phones;
        }, $list);

        return view(
            'admin.info.region',
            [
                'categories' => $categories,
                'value' => $value
            ]
        );
    }
}
