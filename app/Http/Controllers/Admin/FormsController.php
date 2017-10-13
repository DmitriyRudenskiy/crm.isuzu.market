<?php
namespace App\Http\Controllers\Admin;

use App\Repositories\FormsRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FormsController extends Controller
{
    /**
     * @param FormsRepository $repository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(FormsRepository $repository)
    {
        return view(
            'admin.forms.index',
            [
                'list'=> $repository->getAllList()
            ]
        );
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add()
    {
        return view('admin.forms.add');
    }

    /**
     * @param $id
     * @param FormsRepository $repository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id, FormsRepository $repository)
    {
        return view(
            'admin.forms.edit',
            [
                'model' => $repository->find($id)
            ]
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function insert(Request $request, FormsRepository $repository)
    {
        $data = array_map(
            'trim',
            $request->only(
                [
                    'form_title',
                    'name_label',
                    'phone_label',
                    'name_placeholder',
                    'phone_placeholder',
                    'button_title',
                    'form_description',
                    'message_label',
                    'message_placeholder',
                    'in_modal'
                ]
            )
        );

        $model = $repository->add($data);

        if (empty($model->id)) {
            throw new \RuntimeException();
        }

        return redirect()->route('admin_forms_index');
    }

    public function update(Request $request, FormsRepository $repository)
    {
        $id = $request->get('id');

        $data = array_map(
            'trim',
            $request->only(
                [
                    'form_title',
                    'name_label',
                    'phone_label',
                    'name_placeholder',
                    'phone_placeholder',
                    'button_title',
                    'form_description',
                    'message_label',
                    'message_placeholder',
                    'in_modal'
                ]
            )
        );

        $repository->update($data, $id);

        return redirect()->route('admin_forms_index');
    }

    public function hide($id, FormsRepository $repository)
    {
        $repository->update(['visible' => false], $id);

        return redirect()->route('admin_forms_index');
    }

    public function show($id, FormsRepository $repository)
    {
        $repository->update(['visible' => true], $id);

        return redirect()->route('admin_forms_index');
    }
}
