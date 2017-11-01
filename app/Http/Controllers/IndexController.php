<?php
namespace App\Http\Controllers;

use App\Repositories\PhonesRepository;
use Illuminate\Routing\Controller;

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
}
