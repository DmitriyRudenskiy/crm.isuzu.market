<?php
namespace App\Console;

use App\Repositories\PhonesRepository;
use App\Repositories\RegionsRepository;
use App\Service\GetRegionApi;
use Illuminate\Console\Command;

class FillEmptyRegions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:empty:regions';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(PhonesRepository $phonesRepository, RegionsRepository $regionsRepository, GetRegionApi $regionService)
    {
        $list = $phonesRepository->findWhere(["region_id" => null]);

        if ($list->count() < 1) {
            $this->info("All good");
            return null;
        }

        foreach ($list as $value) {
            $this->info("Check for: +7" . $value->number);

            $regionName = $regionService->get($value->number);

            $this->info("Find region: " . $regionName);

            $region = $regionsRepository->get($regionName);

            if ($region === null) {
                $region = $regionsRepository->add($regionName);
            }

            $value->region_id = $region->id;
            $value->save();
        }
    }
}
