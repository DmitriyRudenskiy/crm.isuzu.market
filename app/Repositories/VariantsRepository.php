<?php
namespace App\Repositories;

use App\Entities\Variants;
use Prettus\Repository\Eloquent\BaseRepository;

class VariantsRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Variants::class;
    }

    public function getList()
    {
        return (new $this->model())
            ->whereNotNull('parent_id')
            ->get();
    }
}
