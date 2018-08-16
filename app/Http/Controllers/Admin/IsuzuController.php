<?php
namespace App\Http\Controllers\Admin;

use App\Entities\Parts\Phones;
use App\Entities\Process\Copy;
use App\Entities\Process\Workers;
use App\Service\GetRegionApi;
use App\Service\Telegram\ToptkClient;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

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
        $regionService = new GetRegionApi();
        try {
            $phone->city = $regionService->get($phone->number);
        } catch (\Exception $e) {
            $phone->city = $e->getMessage();
        }

        $phone->save();

        //Создаём процесс
        $copy = new Copy();
        $copy->process_id = 5;
        $copy->name = "Комментарии по клиенту " . $phone->number;
        $copy->save();

        $phone->copy_id = $copy->id;
        $phone->save();


        $message = sprintf(
            "%s // %s // Список действий по клиенту: http://crm.isuzu.market/process/view/%d",
            $phone->number,
            $phone->source,
            $copy->id
        );

        // telegram
        $client = new ToptkClient();
        $client->send($message);

        // mailgin
        Mail::raw($message, function (Message $message) {
            $message->to(env('MAIL_TO_NOTIFICATION'));
            $message->subject('Новый клиент');
        });
    }
}
