<?php
namespace App\Repositories;

use App\Entities\Phones;
use Illuminate\Support\Facades\DB;
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

    public function get($text)
    {
        return Phones::where('number', $text)->first();
    }

    public function add(array $data)
    {
        try {
            $model = Phones::forceCreate($data);
        } catch (\Exception $e) {
            return null;
        }

        return $model->id;
    }

    public function getNew()
    {
        return $this->scopeQuery(function($query){
            return $query->orderBy('id','asc');
        })->findWhere(['call' => null]);
    }

    public function getPhonesInDay()
    {
        $sql = "SELECT DATE_FORMAT(p.created_at, '%Y-%m-%d') day, COUNT(*) phones
                FROM phones p
                GROUP BY DATE_FORMAT(p.created_at, '%Y-%m-%d')
                ORDER BY DATE_FORMAT(p.created_at, '%Y-%m-%d');";

        $results = DB::select( DB::raw($sql));

        return $results;
    }
}
