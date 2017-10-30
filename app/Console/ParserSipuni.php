<?php
namespace App\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ParserSipuni extends Command
{
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
    public function handle()
    {
        $file =  storage_path('stat.csv');

        if (!file_exists($file)) {

        }

        dd($file);


        /*
        if (empty(env('ADMIN_LOGIN')) || empty(env('ADMIN_PASSWORD'))) {
            throw new \RuntimeException();
        }

        try {
            DB::table('users')->insert([
                'name' => env('ADMIN_LOGIN'),
                'email' => env('ADMIN_LOGIN'),
                'password' => bcrypt(env('ADMIN_PASSWORD'))
            ]);
        } catch (\Exception $e) {
            $user = DB::table('users')
                ->where('name', env('ADMIN_LOGIN'))
                ->first();

            $this->info('User id: ' . $user->id .' update.');
            return;
        }

        $this->info('User created!');
        */
    }
}
