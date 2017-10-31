<?php
namespace App\Console;

use App\Repositories\PhonesRepository;
use App\Service\Phone;
use App\Service\Sipuni\Loader;
use App\Service\Sipuni\Query;
use App\Service\Telegram;
use Illuminate\Console\Command;

class ParserSipuni extends Command
{
    const URL = "https://sipuni.com/api/statistic/export";

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parser:sipuni';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Loader $loader, Phone $service, PhonesRepository $repository)
    {
        $list = [
            [env('SIPUNI_FURA_USER'), env('SIPUNI_FURA_KEY')],
            [env('SIPUNI_TRUCK_USER'), env('SIPUNI_TRUCK_KEY')]
        ];


        foreach ($list as $value) {
            $query = new Query($value[0], $value[1]);
            $result = $loader->setUrl(self::URL)->get($query);

            $csv = array_map(function($string) {
                return str_getcsv($string, ";");
            }, explode("\n", $result));

            $data = [];

            foreach ($csv as $value) {
                if ($value[0] == "Исходящий" && $value[1] == "Отвечен") {
                    $phone = $service->parsing($value[5]);

                    if (!empty($phone) && !isset($data[$phone])) {
                        $date = \DateTime::createFromFormat('d.m.Y H:i:s', $value[2]);
                        $data[$phone] =  $date;
                    }
                }
            }

            foreach ($data as $key => $value) {
                $entity = $repository->get($key);

                if ($entity !== null && $entity->id > 0 && empty($entity->call)) {
                    $entity->call = $value;
                    $entity->save();

                    $this->sendMessageToTelegram($entity);
                }
            }
        }
    }

    protected function sendMessageToTelegram($data)
    {
        $interval = $data->call->diff(
            \DateTime::createFromFormat('Y-m-d H:i:s', $data->created_at)
        );

        $data->diff = $interval->format('%a') * 24 + $interval->format('%h');

        $message = view("admin.telegram.notification", $data)->render();

        return (new Telegram())->send($message);
    }
}
