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
        $sql = "SELECT r.name, COUNT(*) phones
                FROM regions r
                INNER JOIN phones p ON p.region_id=r.id
                GROUP BY p.region_id
                ORDER BY phones desc;";

        $results = DB::select( DB::raw($sql));

        return $results;
    }
}
