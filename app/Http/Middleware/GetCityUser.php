<?php

namespace App\Http\Middleware;

use Closure;
use GeoIp2\Database\Reader;
use Illuminate\Support\Facades\DB;

class GetCityUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!empty($_SERVER["HTTP_X_REAL_IP"])) {
            $result = $this->getCityForIp($_SERVER["HTTP_X_REAL_IP"]);

            if (!empty($result)) {
                putenv('APP_CITY=' . $result);
                return $next($request);
            }
        }

        $result = $this->getCityForIp($request->ip());

        if (!empty($result)) {
            putenv('APP_CITY=' . $result);
        }

        return $next($request);
    }

    public function getCityForIp($ip)
    {
        $filename = storage_path('city') . DIRECTORY_SEPARATOR . 'GeoLite2-City.mmdb';

        $reader = new Reader($filename);

        try {
            $record = $reader->city(trim($ip));
        } catch (\Exception $e) {
            return null;
        }

        if (!empty($record->city->geonameId)) {
            $data = DB::table('cities')
                ->where('id', $record->city->geonameId)
                ->first();

            if (!empty($data->pred)) {
                return $data->pred;
            }
        }

        return null;
    }
}
