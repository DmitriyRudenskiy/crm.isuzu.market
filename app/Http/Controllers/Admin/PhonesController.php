<?php
namespace App\Http\Controllers\Admin;

use App\Repositories\PhonesRepository;
use App\Service\Phone;
use App\Service\Telegram;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class PhonesController extends Controller
{

    public function index(Request $request, PhonesRepository $repository)
    {
        $query = $request->get('q');
        $list = $repository->getList($query);

        return view(
            'admin.phones.index',
            [
                'list' => $list,
                'email' => $query
            ]
        );
    }

    public function add()
    {
        return view('admin.phones.add');
    }

    public function insert(Request $request, PhonesRepository $repository, Phone $phoneService, Telegram $telegram)
    {
        $phone = $phoneService->parsing($request->get("phone"));
        $source = $request->get("source");

        if (!$phoneService->isValid($phone)) {
            throw new \InvalidArgumentException();
        }

        if ($repository->has($phone)) {
            return redirect()->route('admin_phones_index', ['error' => 'has-' . $phone]);
        }

        $status = $repository->add($phone, $source);

        if ($status > 0) {
            $telegram->send('>>> Добавлен новый телефон: ' . $phone);
        }

        return redirect()->route('admin_phones_index');
    }
}
