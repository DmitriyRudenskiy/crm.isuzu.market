<?php
namespace App\Http\Controllers\Front;

use App\Entities\Process\Copy;
use App\Entities\Process\Process;
use App\Entities\Process\Workers;
use App\Entities\Process\Tasks;
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

    public function view($processId, Process $processRepository, Workers $workersRepository, Copy $copyRepository)
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

    public function task(Request $request, Tasks $taskRepository)
    {
        $id = $request->get('id');
        $comment = trim($request->get('comment'));

        $task = $taskRepository->findOrFail($id);

        $task->update([
            'is_ready' => new \DateTime(),
            "comment" => $comment
        ]);

        return redirect()->route('front_process_view', ["id" => $task->process_id]);
    }

    public function copy(Request $request, Copy $copyRepository, Process $processRepository)
    {
        $copy = $copyRepository->create($request->only(["process_id", "name"]));

        return redirect()->route(
            'front_process',
            [
                "id" => $copy->process()->first()->worker_id
            ]
        );
    }
}
