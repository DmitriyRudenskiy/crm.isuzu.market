<?php
namespace App\Console;

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
    public function handle(SparePartClient $client)
    {
        $message = "У нас есть запчасть на машину?
Два часа назад был запланирован заезд в сервис.
Проблема наличия запчастей не решена.";

        $client->send($message);

        $this->info($message);
    }
}
