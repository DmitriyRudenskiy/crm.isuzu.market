<?php
namespace App\Http\Controllers\Admin;

use App\Entities\Parts\Phones;
use App\Entities\Process\Copy;
use App\Entities\Process\Workers;
use App\Service\Telegram\ToptkClient;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class IsuzuController extends Controller
{

    public function index(Request $request, Phones $repository, Workers $workersRepository)
    {
        $query = trim($request->get('q'));

        if (empty($query)) {
            $list = $repository->orderBy('id', 'desc')->paginate(15);
        } else {
            $list = $repository->where('number', 'like', '%' . $query . '%')
                ->orderBy('id', 'desc')
                ->paginate(15);
        }

        return view(
            'admin.isuzu.index',
            [
                'query' => $query,
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

        $this->createProcess($phone);

        return redirect()->back();
    }

    public function add(Workers $workersRepository)
    {
        return view(
            'admin.isuzu.add' ,
            [
                'workers' => $workersRepository->all()
            ]
        );
    }

    public function insert(Request $request, Phones $repository)
    {
        $data = $request->only(["number", "worker_id", "source", "comment"]);
        $phone = $repository->create($data);
        $this->createProcess($phone);

        return redirect()->route('admin_isuzu_index', ["success" => true]);
    }

    protected function createProcess(Phones $phone)
    {
        //Создаём процесс
        $copy = new Copy();
        $copy->process_id = 5;
        $copy->name = "Звонок заказчику по номеру " . $phone->number;
        $copy->save();

        $phone->copy_id = $copy->id;
        $phone->save();

        $client = new ToptkClient();
        $message = sprintf("%s Работай тут: /process/view/%d", "+7" . $phone->number, $copy->id);
        $client->send($message);
    }
}
