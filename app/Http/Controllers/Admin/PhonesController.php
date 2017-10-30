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

    public function view($id, Request $request, PhonesRepository $repository)
    {
        $phone = $repository->find($id);
        $error = $request->get("error", false);

        return view(
            'admin.phones.view',
            [
                "phone" => $phone,
                "error" => $error
            ]
        );
    }

    public function add()
    {
        return view('admin.phones.add');
    }

    public function insert(Request $request, PhonesRepository $repository, Phone $phoneService)
    {
        $data = array_map(
            'trim',
            $request->only(
                [
                    "vendor",
                    "group_city",
                    "city",
                    "is_sib",
                    "comment"
                ]
            )
        );

        $data["is_sib"] = !empty($data["is_sib"]);

        $phone = $phoneService->parsing($request->get("phone"));

        // неправильный номер телефона
        if (!$phoneService->isValid($phone)) {
            throw new \InvalidArgumentException();
        }

        $data["number"] = $phone;

        $entity = $repository->get($phone);

        if ($entity !== null) {
            return redirect()->route('admin_phones_view', ["id" => $entity->id, "error" => true]);
        }

        $repository->add($data);

        $this->sendMessageToTelegramm($data);

        return redirect()->route('admin_phones_index', ["success" => true]);
    }

    protected function sendMessageToTelegramm($data)
    {
        $message = view("admin.telegram.notification", $data)->render();

        $telegram = new Telegram();
        return $telegram->send($message);
    }
}
