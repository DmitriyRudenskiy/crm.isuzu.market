<?php
namespace App\Http\Controllers\Admin;

use App\Entities\SpareParts;
use App\Entities\Tasks;
use App\Service\Telegram;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use DateTime;
use App\Service\Telegram\TaskClient;

class TaskController extends Controller
{

    public function index(Tasks $repository)
    {
        $list = $repository->orderBy('id', 'desc')->paginate();

        return view(
            'admin.task.index',
            [
                'list' => $list
            ]
        );
    }

    public function add()
    {
        return view('admin.task.add');
    }

    public function insert(Request $request, Tasks $repository, TaskClient $client)
    {
        $data = array_map(
            'trim',
            $request->only(
                [
                    "worker",
                    "comment",
                    "period"
                ]
            )
        );

        $data["period"] = DateTime::createFromFormat('j.m.Y H:i', $data["period"]);

        $repository->forceCreate($data);

        // отправляем новое сообщение
        $message = sprintf("Назначена новая задача для %s\nЗадача: %s", $data["worker"], $data["comment"]);
        $client->send($message);

        return redirect()->route('admin_task_index', ["success" => true]);
    }

    public function success($sparePartId, Tasks $repository, TaskClient $client)
    {
        $sparePart = $repository->where('id', $sparePartId)->first();

        if ($sparePart !== null) {
            $sparePart->is_ready = true;
            $sparePart->save();
        }

        $message = sprintf(
            "Ответственный %s выполнил задачу:\n%s",
            $sparePart->worker,
            $sparePart->comment
        );

        $client->send($message);

        return redirect()->route('admin_task_index', ["success" => true]);
    }
}
