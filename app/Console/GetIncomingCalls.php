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
        $list = [
            "fura" => [env('SIPUNI_FURA_USER'), env('SIPUNI_FURA_KEY')],
            "truck" => [env('SIPUNI_TRUCK_USER'), env('SIPUNI_TRUCK_KEY')],
            "atorgi" => [env('SIPUNI_ATORGI_USER'), env('SIPUNI_ATORGI_KEY')]
        ];

        foreach ($list as $key => $value) {

            $this->info('Check for :' . $key);

            $query = new Query($value[0], $value[1]);

            $result = $loader->setUrl(ParserSipuni::URL)->get($query);

            $csv = array_map(function($string) {
                return str_getcsv($string, ";");
            }, explode("\n", $result));

            foreach ($csv as $value) {
                if (!empty($value[4])) {

                    $phone = $service->parsing($value[4]);

                    $this->info('Find phone number : +7' . $phone);

                    if ($value[0] == "Входящий") {
                        $date = null;

                        if ($value[1] != "Не отвечен") {
                            $date = \DateTime::createFromFormat('d.m.Y H:i:s', $value[2]);
                        }

                        $this->check($repository, $phone, $date);
                    }
                }
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
