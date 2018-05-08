<?php
namespace App\Http\Controllers\Admin;

use App\Entities\Process\Workers;
use App\Entities\Regulations;
use App\Entities\Tasks;
use App\Service\Telegram;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use DateTime;
use App\Service\Telegram\TaskClient;

/**
 * Class RegulationsController
 * @package App\Http\Controllers\Admin
 */
class RegulationsController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $list = [];

        return view('admin.regulations.index', ['list' => $list]);
    }

    /**
     * @param Workers $workersRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add(Workers $workersRepository)
    {
        $workers = $workersRepository->all();

        return view(
            'admin.regulations.add',
            [
                'workers' => $workers
            ]
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function insert(Request $request)
    {
        $workerId = (int)$request->get("worker_id");
        $content = trim($request->get("content"));

        $regulations = new Regulations();
        $regulations->worker_id = $workerId;
        $regulations->content = $content;
        $regulations->save();

        return redirect()->route('admin_regulations_index', ["success" => true]);
    }
}
