<?php
namespace App\Repositories;

use App\Entities\Phones;
use App\Entities\Regions;
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
}
