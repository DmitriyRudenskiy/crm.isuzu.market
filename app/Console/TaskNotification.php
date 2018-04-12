<?php
namespace App\Console;

use App\Entities\Tasks;
use App\Service\Telegram\TaskClient;
use Illuminate\Console\Command;
use DateTime;

class TaskNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:notification';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Tasks $repository)
    {

        $list = $repository->where('is_ready', false)
            ->get();

        $this->info("Is now: " . date("Y-m-d H:i:s", strtotime("+7 hours")));
        $this->info("Find in work: " . $list->count());

        foreach ($list as $item) {
            $this->info("Check is: " . strtotime($item->period));

            if ((strtotime("+7 hours") - strtotime($item->period)) < 0) {
                $this->send($item);
            }
        }

        $this->info("Finish");
    }

    protected function send(Tasks $task)
    {
        $interval = (int)round((strtotime("+7 hours") - strtotime($task->period)) / 60);

        $message = sprintf(
            "Ответственный %s не выполнил задачу поставленую %d минут назад\n%s",
            $task->worker,
            $interval,
            $task->comment
        );

        $client = new TaskClient();
        $client->send($message);
    }
}
