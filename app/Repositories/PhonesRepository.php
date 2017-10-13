<?php
namespace App\Repositories;

use App\Entities\Phones;
use Prettus\Repository\Eloquent\BaseRepository;

class PhonesRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Phones::class;
    }

    public function getList($query = null)
    {
        if (empty($query)) {
            $list = Phones::orderBy('id', 'desc')->paginate();
        } else {
            $list = Phones::where('number', 'like', '%' . $query . '%')
                ->orderBy('id', 'desc')
                ->paginate()
                ->appends(['q' => $query]);
        }

        return $list;
    }

    public function has($text)
    {
        $phone = Phones::where('number', $text)->first();

        return $phone !== null;
    }

    public function add($phone, $source)
    {
        $model = new Phones();
        $model->number = $phone;
        $model->source = $source;

        try {
            $model->save();
        } catch (\Exception $e) {

        }

        return $model->id;
    }
}
