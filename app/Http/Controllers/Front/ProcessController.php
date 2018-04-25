<?php
namespace App\Http\Controllers\Front;

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

    public function view($processId, Process $processRepository, Workers $workersRepository)
    {
        $process = $processRepository->findOrFail($processId);
        $user = $workersRepository->findOrFail($process->worker_id);

        return view(
            'front.process.view',
            [
                "user" => $user,
                'process' => $process
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
}
