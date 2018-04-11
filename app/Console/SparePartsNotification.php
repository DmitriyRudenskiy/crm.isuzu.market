<?php
namespace App\Console;

use App\Entities\SpareParts;
use App\Service\Telegram\SparePartClient;
use Illuminate\Console\Command;

class SparePartsNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spare:parts:notification';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(SpareParts $repository)
    {

        $list = $repository->where("is_ready", false)->get();

        foreach ($list as $item) {
            $this->send($item);
        }

        $this->info("Finish");
    }

    protected function send(SpareParts $sparePart)
    {
         $client = new SparePartClient();

        $message = sprintf(
            "У нас есть запчасть на машину?
Два часа назад был запланирован заезд в сервис.
Проблема наличия запчастей не решена.
Запчасти подготовлены для:\nДата заезда: %s\nКомпания: %s\nVIN: %s\nВид работ: %s\nПримечание: %s",
            $sparePart->start_work,
            $sparePart->company,
            $sparePart->vin,
            $sparePart->type,
            $sparePart->comment
        );

        $client->send($message);

        $client->send($message);
    }
}
