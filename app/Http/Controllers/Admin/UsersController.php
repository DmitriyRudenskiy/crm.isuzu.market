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
class UsersController extends Controller
{

    /**
     * @param Workers $workersRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Workers $workersRepository)
    {
        $list = $workersRepository->orderBy('name')->paginate();

        return view('admin.users.index', ['list' => $list]);
    }
}
