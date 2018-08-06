<?php
namespace App\Http\Controllers\Front;

use App\Entities\Parts\Phones;
use App\Entities\Process\Copy;
use App\Entities\Process\Process;
use App\Entities\Process\Status;
use App\Entities\Process\Workers;
use App\Service\Telegram;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    public function index($userId, Process $processRepository, Workers $workersRepository)
    {
        $user = $workersRepository->findOrFail($userId);
        $list = $processRepository->where("worker_id", $user->id)->get();

        return view(
            'front.process.index',
            [
                "user" => $user,
                'list' => $list
            ]
        );
    }

    public function view($processId, Workers $workersRepository, Copy $copyRepository)
    {
        $copy = $copyRepository->findOrFail($processId);
        $process = $copy->process()->first();
        $user = $workersRepository->findOrFail($process->worker_id);

        return view(
            'front.process.view',
            [
                "user" => $user,
                'process' => $process,
                "copy" => $copy
            ]
        );
    }

    public function task(Request $request, Status $statusRepository, Phones $phones)
    {
        $now =  new \DateTime();
        $now->modify('+7 hours');

        $data = [
            "copy_id" => (int)$request->get("copy_id"),
            "task_id" => (int)$request->get("task_id"),
            "is_ready" => $now,
            "comment" => trim($request->get('comment')),
        ];

        $statusRepository->create($data);


        $nasNewSystem = $phones->where("copy_id", $data["copy_id"])->first();

        if ($nasNewSystem !== null) {
            $client = new Telegram\ToptkNotificationClient();
            $message = sprintf(
                "По клиенту: %s // выполнено действие: %s // Список действий по клиенту: http://crm.isuzu.market/process/view/%d",
                $nasNewSystem->source,
                $data["comment"],
                $data["copy_id"]
            );
            $client->send($message);
        }

        return redirect()->route('front_process_view', ["id" => $data["copy_id"]]);
    }

    public function copy(Request $request)
    {
        //
        $processId = (int)$request->get("process_id");
        $name = trim($request->get("name"));

        //
        $copy = new Copy();
        $copy->process_id = $processId;
        $copy->name = $name;
        $copy->save();

        //
        if ($processId == 2) {
            $message = sprintf("В автосервис  заехал автомобиль '%s' http://crm.isuzu.market/supervisor", $name);
            $telegram = new Telegram();
            $telegram->send($message);
        }

        return redirect()->route(
            'front_process',
            [
                "id" => $copy->process()->first()->worker_id
            ]
        );
    }
}
