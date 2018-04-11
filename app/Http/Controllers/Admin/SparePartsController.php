<?php
namespace App\Http\Controllers\Admin;

use App\Entities\SpareParts;
use App\Service\Telegram;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class SparePartsController extends Controller
{

    public function index(SpareParts $repository)
    {
        $list = $repository->paginate();

        return view(
            'admin.part.index',
            [
                'list' => $list
            ]
        );
    }

    public function add()
    {
        return view('admin.part.add');
    }

    public function insert(Request $request, SpareParts $repository, Telegram\SparePartClient $client)
    {
        $data = array_map(
            'trim',
            $request->only(
                [
                    "start_work",
                    "company",
                    "vin",
                    "type",
                    "comment"
                ]
            )
        );

        $repository->forceCreate($data);

        $message = sprintf(
            "НОВАЯ заявка\nДата заезда: %s\nКомпания: %s\nVIN: %s\nВид работ: %s\nПримечание: %s",
            $data["start_work"],
            $data["company"],
            $data["vin"],
            $data["type"],
            $data["comment"]
        );

        $client->send($message);

        return redirect()->route('admin_spare_parts_index', ["success" => true]);
    }

    public function success($sparePartId, SpareParts $repository, Telegram\SparePartClient $client)
    {
        $sparePart = $repository->where('id', $sparePartId)->first();

        if ($sparePart !== null) {
            $sparePart->is_ready = true;
            $sparePart->save();
        }

        $message = sprintf(
            "Тут всё хорошо!\nДата заезда: %s\nКомпания: %s\nVIN: %s\nВид работ: %s\nПримечание: %s",
            $sparePart->start_work,
            $sparePart->company,
            $sparePart->vin,
            $sparePart->type,
            $sparePart->comment
        );

        $client->send($message);

        return redirect()->route('admin_spare_parts_index', ["success" => true]);
    }
}
