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

    public function index(Tasks $repository, Request $request)
    {
        $showIsReady = ($request->get('is_ready', 0) > 0);
        $worker = $request->get('worker');

        $query = $repository->orderBy('period', 'asc');

        if (!$showIsReady) {
            $query->where("is_ready", false);
        }

        if (!empty($worker)) {
            $query->where("worker", $worker);
        }

        $list = $query->paginate();

        return view(
            'admin.task.index',
            [
                'list' => $list,
                'is_ready' => $showIsReady,
                'worker' => $worker
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
        $message = sprintf(
            "Назначена новая задача для %s\nВыполнить до %s\nЗадача: %s",
            $data["worker"],
            $data["period"]->format('d.m.Y H:i:s'),
            $data["comment"]
        );

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

    public function edit($taskId, Tasks $repository, TaskClient $client)
    {
        $task = $repository->where('id', $taskId)->first();

        if ($task === null) {
            throw new \RuntimeException();
        }

        return view('admin.task.add', ['task' => $task]);
    }

    public function update(Request $request, Tasks $repository, TaskClient $client)
    {
        $taskId = $request->get('id');

        $task = $repository->where('id', $taskId)->first();

        if ($task === null) {
            throw new \RuntimeException();
        }

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

        $task->period = DateTime::createFromFormat('j.m.Y H:i', $data["period"]);
        $task->worker = $data["worker"];
        $task->comment = $data["comment"];
        $task->save();

        return redirect()->route('admin_task_index', ["success" => true]);
    }

    public function request($taskId, Tasks $repository, TaskClient $client)
    {
        $task = $repository->where('id', $taskId)->first();

        if ($task !== null) {
            $message = sprintf(
                "%s поясните причину невыполнения задания %s и напишите сколько времени вам потребуется для выполнения задачи",
                $task->worker,
                $task->comment
            );

            $client->send($message);
        }

        return redirect()->route('admin_task_index', ["success" => true]);
    }

    protected function getWorkers()
    {
        return [

        ];
    }
}
