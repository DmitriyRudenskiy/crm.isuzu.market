<?php
namespace App\Http\Controllers\Admin;

use App\Entities\Process\Process;
use App\Entities\Process\Tasks;
use App\Entities\Process\Workers;
use App\Service\Telegram;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class ProcessController extends Controller
{

    public function index(Process $processRepository)
    {
        $list = $processRepository->paginate();

        return view(
            'admin.process.index',
            [
                'list' => $list
            ]
        );
    }

    public function add(Workers $workersRepository)
    {
        $workers = $workersRepository->all();

        return view(
            'admin.process.add',
            [
                'workers' => $workers
            ]
        );
    }

    public function insert(Request $request, Process $processRepository, Tasks $tasksRepository)
    {
        $name = trim($request->get('name'));
        $workerId = (int)$request->get('worker_id');
        $task = trim($request->get('task'));

        $process = $processRepository->forceCreate([
            'name' => $name,
            'worker_id' => $workerId
        ]);

        $tasksRepository->forceCreate([
            'title' => $task,
            'process_id' => $process->id
        ]);

        //return redirect()->route('admin_process_index', ["success" => true]);
        return redirect()->route('admin_process_edit', ["id" => $process, "status[add]" => true]);
    }

    /**
     * @param $processId
     * @param Process $processRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($processId, Process $processRepository, Workers $workersRepository)
    {
        $process = $processRepository->findOrFail($processId);
        $workers = $workersRepository->all();

        return view(
            'admin.process.edit',
            [
                'process' => $process,
                'workers' => $workers
            ]
        );
    }

    public function update(Request $request, Process $processRepository, Tasks $taskRepository)
    {
        $processData = $request->get('process');
        $tasksData = $request->get('tasks');
        $taskData = $request->get('task');

        $process = $processRepository->findOrFail($processData["id"]);
        $process->update($processData);

        foreach ($tasksData as $value) {
            $task = $taskRepository->findOrFail($value["id"]);
            $task->update($value);
        }

        if (!empty($taskData)) {
            $taskRepository->create(
                [
                    'title' => $taskData,
                    'process_id' => $process->id
                ]
            );
        }

        return redirect()->route('admin_process_edit', ["id" => $process, "status" => true]);
    }

    public function delete($taskId, Tasks $taskRepository)
    {
        $task = $taskRepository->findOrFail($taskId);
        $processId = $task->process_id;

        $count = $taskRepository->where("process_id", $processId)->count();

        if ($count > 1) {
            $task->delete();
        }

        return redirect()->route('admin_process_edit', ["id" => $processId, "status_remove" => true]);
    }

}
