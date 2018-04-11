<?php
namespace App\Http\Controllers\Admin;

use App\Entities\SpareParts;
use App\Service\Telegram;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class SparePartsController extends Controller
{

    public function index(SpareParts $repository)
    {
        $list = $repository->paginate();

        return view(
            'admin.part.index',
            [
                'list' => $list
            ]
        );
    }

    public function add()
    {
        return view('admin.part.add');
    }

    public function insert(Request $request, SpareParts $repository)
    {
        $data = array_map(
            'trim',
            $request->only(
                [
                    "start_work",
                    "company",
                    "vin",
                    "type",
                    "comment"
                ]
            )
        );

        $repository->forceCreate($data);

        return redirect()->route('admin_spare_parts_index', ["success" => true]);
    }
}
