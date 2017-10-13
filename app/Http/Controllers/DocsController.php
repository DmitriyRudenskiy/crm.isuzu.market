<?php
namespace App\Http\Controllers;

use App\Entities\Docs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class DocsController extends Controller
{
    public function index()
    {
        $list = Docs::all();

        return view('admin.docs.index', ['list' => $list]);
    }

    public function add(Request $request)
    {
        $data = $request->only(['name']);

        Docs::forceCreate($data);

        return redirect()->route('admin_docs_index');
    }

    /**
     * @param int $id
     * @param BenefitsRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function hide($id)
    {
        $model = Docs::where('id', $id)->first();

        if ($model !== null) {
            $model->visible = false;
            $model->save();
        }

        return redirect()->route('admin_docs_index');
    }

    /**
     * @param int $id
     * @param BenefitsRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        $model = Docs::where('id', $id)->first();

        if ($model !== null) {
            $model->visible = true;
            $model->save();
        }

        return redirect()->route('admin_docs_index');
    }
}




