<?php
namespace App\Http\Controllers\Admin;

use App\Entities\Parts\Phones;
use App\Service\Telegram;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class IsuzuController extends Controller
{

    public function index(Phones $repository)
    {
        $list = $repository->paginate(10);

        return view(
            'admin.isuzu.index',
            [
                'list' => $list,
                'email' => ''
            ]
        );
    }
}
