<?php
namespace App\Console;

use App\Entities\Phones;
use App\Repositories\PhonesRepository;
use App\Service\Phone;
use App\Service\Sipuni\Loader;
use App\Service\Sipuni\Query;
use App\Service\Telegram;
use Illuminate\Console\Command;

class GetIncomingCalls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'incoming:calls';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Loader $loader, PhonesRepository $repository, Phone $service)
    {
        $query = new Query(env('SIPUNI_TRUCK_USER'), env('SIPUNI_TRUCK_KEY'));

        $result = $loader->setUrl(ParserSipuni::URL)->get($query);

        $csv = array_map(function($string) {
            return str_getcsv($string, ";");
        }, explode("\n", $result));

        foreach ($csv as $value) {
            if (!empty($value[4]) && $value[0] == "Входящий") {
                $phone = $service->parsing($value[4]);

                $date = null;

                if ($value[1] != "Не отвечен") {
                    $date = \DateTime::createFromFormat('d.m.Y H:i:s', $value[2]);
                }

                $this->check($repository, $phone, $date);
            }
        }
    }

    public function check(PhonesRepository $repository, $phone, $date)
    {
        if ($repository->get($phone) !== null) {
            return false;
        }

        $model = new Phones();
        $model->number = $phone;
        $model->call = $date;
        $model->vendor = 'входящие Трак-Прайс';
        $model->group_city = ' ';
        $model->save();

        $message = view("admin.telegram.import", $model)->render();

        return (new Telegram())->send($message);
    }
}
