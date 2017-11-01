<?php
namespace App\Console;

use App\Repositories\PhonesRepository;
use App\Service\Phone;
use App\Service\Sipuni\Loader;
use App\Service\Sipuni\Query;
use App\Service\Telegram;
use Illuminate\Console\Command;

class RemindToCall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remind:call';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(PhonesRepository $repository)
    {
        $list = $repository->getNew();

        if (sizeof($list) < 1) {
            $this->info("All good");
            return null;
        }

        foreach ($list as $value) {
            sleep(3);
            $this->sendMessageToTelegram($value);
        }

        $this->info("Send: " . sizeof($list));
    }

    protected function sendMessageToTelegram($data)
    {
        $message = view("admin.telegram.remind", $data)->render();

        return (new Telegram())->send($message);
    }
}
