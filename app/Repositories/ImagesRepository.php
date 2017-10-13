<?php
namespace App\Repositories;

use App\Entities\Images;
use Prettus\Repository\Eloquent\BaseRepository;

class ImagesRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Images::class;
    }

    public function getList($variantId)
    {
        return (new $this->model())
            ->where('variant_id', $variantId)
            ->orderBy('priority')
            ->get();
    }
}
