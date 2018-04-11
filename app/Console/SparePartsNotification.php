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
        $interval = (int)round((time() - strtotime($sparePart->created_at)) / 60);

        $message = sprintf(
            "Проблема наличия запчастей не решена (Прошло %d минут).
            Дата заезда: %s\nКомпания: %s\nАвтомобиль: %s\nВид работ: %s\nПримечание: %s",
            $interval,
            $sparePart->start_work,
            $sparePart->company,
            $sparePart->vin,
            $sparePart->type,
            $sparePart->comment
        );

        $client = new SparePartClient();
        $client->send($message);
    }
}
