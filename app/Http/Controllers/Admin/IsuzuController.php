<?php
namespace App\Http\Controllers\Admin;

use App\Entities\Parts\Phones;
use App\Entities\Process\Workers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class IsuzuController extends Controller
{

    public function index(Phones $repository, Workers $workersRepository)
    {
        $list = $repository->orderBy('id', 'desc')->paginate(15);

        return view(
            'admin.isuzu.index',
            [
                'list' => $list,
                'email' => '',
                'workers' => $workersRepository->all()
            ]
        );
    }

    public function set(Request $request, Phones $repository)
    {
        $id = (int)$request->get('id');
        $phone = $repository->findOrFail($id);

        $phone->worker_id = (int)$request->get('worker_id');
        $phone->save();

        return redirect()->back();
    }
}
