<?php
namespace App\Http\Controllers\Admin;

use App\Entities\Titles;
use App\Repositories\TitlesRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TitlesController extends Controller
{
    /**
     * @param TitlesRepository $repository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(TitlesRepository $repository)
    {
        return view(
            'admin.titles.index',
            [
                'list'=> $repository->getList()
            ]
        );
    }

    /**
     * @param Request $request
     * @param TitlesRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, TitlesRepository $repository)
    {
        $data = $request->all();
        $keys = Titles::getKeys();

        foreach ($data as $key => $value) {
            if (isset($keys[$key])) {
                $repository->add($key, trim($value));
            }
        }

        return redirect()->route('admin_titles_index');
    }
}
