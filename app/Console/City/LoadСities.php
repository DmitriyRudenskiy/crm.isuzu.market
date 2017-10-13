<?php
namespace App\Console\City;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LoadСities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'city:load';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Start');

        $list = DB::table('cities')
            ->where('pred', null)
            ->get();

        if ($list->count() < 1) {
            $this->info('Empty list');
        }

        foreach ($list as $value) {
            $this->check($value);

            sleep(2);
        }
    }

    protected function check($obj)
    {
        $this->info($obj->id);

        $data = $this->getResponse($obj->im);

        if (empty($data["П"])) {
            exit();
        }

        $data = [
            "rod" => $data["Р"],
            "dat" => $data["Д"],
            "vin" => $data["В"],
            "tvor" => $data["Т"],
            "pred" => $data["П"],
        ];

        DB::table('cities')
            ->where('id', $obj->id)
            ->update($data);
    }

    protected function getResponse($name)
    {
        $data = [
            's' => $name,
            'format' => 'json'
        ];

        $url = 'https://ws3.morpher.ru/russian/declension?' . http_build_query($data);

        $content = file_get_contents($url);

        $result = json_decode($content, TRUE);

        if (!empty($result)) {
            return $result;
        }

        return null;
    }

}
