<?php
namespace App\Http\Controllers\Admin;

use App\Repositories\LabelRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LabelController extends Controller
{
    /**
     * @param LabelRepository $repository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(LabelRepository $repository)
    {
        return view(
            'admin.label.index',
            [
                'list' => $repository->get()
            ]
        );
    }

    /**
     * @param int $id
     * @param LabelRepository $repository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id, LabelRepository $repository)
    {
        return view(
            'admin.label.edit',
            [
                'model' => $repository->find($id)
            ]
        );
    }

    /**
     * @param Request $request
     * @param LabelRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, LabelRepository $repository)
    {
        $id = $request->get('id');

        $data = array_map(
            'trim',
            $request->only(
                [
                    'priority',
                    'label',
                ]
            )
        );

        $repository->update($data, $id);

        return redirect()->route('admin_label_index');
    }

    /**
     * @param int $id
     * @param LabelRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function hide($id, LabelRepository $repository)
    {
        $repository->update(['visible' => false], $id);

        return redirect()->route('admin_label_index');
    }

    /**
     * @param int $id
     * @param LabelRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id, LabelRepository $repository)
    {
        $repository->update(['visible' => true], $id);

        return redirect()->route('admin_label_index');
    }
}
