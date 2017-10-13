<?php
namespace App\Http\Controllers\Admin;

use App\Repositories\PhonesRepository;
use Illuminate\Routing\Controller;

class PhonesController extends Controller
{

    public function index(PhonesRepository $repository)
    {
        $list = $repository->get();

        return view(
            'admin.phones.index',
            [
                'list' => $list
            ]
        );
    }
}
