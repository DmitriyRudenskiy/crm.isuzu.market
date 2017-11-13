<?php
namespace App\Repositories;

use App\Entities\Phones;
use App\Entities\Regions;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class RegionsRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Regions::class;
    }

    public function get($name)
    {
        return $this->findByField("name", $name)->first();
    }

    public function add($name)
    {
        return $this->create(["name" => $name]);
    }

    public function getCountPhones()
    {
        $sql = "SELECT regions.name, COUNT(*) phones
                FROM regions
                INNER JOIN phones ON phones.region_id=regions.id
                GROUP BY phones.region_id
                ORDER BY phones desc;";

        $results = DB::select( DB::raw($sql));

        return $results;
    }
}
