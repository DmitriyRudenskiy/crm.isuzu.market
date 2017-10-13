<?php
namespace App\Console\City;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FillingСities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'city:fill';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $filename = storage_path('city') . DIRECTORY_SEPARATOR . 'tmp.json';

        $content = json_decode(file_get_contents($filename));

        foreach ($content as $key => $value) {
            DB::table('cities')->insert(
                [
                    'id' => $key,
                    'im' => $value
                ]
            );
        }
    }
}
