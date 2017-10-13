<?php
namespace App\Http\Controllers;

use App\Entities\Docs;
use App\Entities\DocsParams;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DocsParamsController extends Controller
{
    public function index($docId)
    {
        $model = Docs::where('id', $docId)->first();

        if ($model === null) {
            throw new NotFoundHttpException();
        }

        return view(
            'admin.docs_params.index',
            [
                'doc' => $model,
                'list' => $model->getParams()->get()
            ]
        );
    }

    public function insert(Request $request)
    {
        $data = array_map('trim', $request->only(['doc_id', 'position', 'key', 'value']));

        $this->addToDatabase($data);

        return redirect()->back();
    }

    public function update(Request $request)
    {
        $id = (int)$request->get('id');
        $data = array_map('trim', $request->only(['position', 'key', 'value']));

        $model = DocsParams::where('id', $id)->first();

        if ($model !== null) {
            $model->fill($data);
            $model->save();
        }

        return redirect()->back();
    }

    public function import(Request $request)
    {
        $docId = $request->get('id');

        $model = Docs::where('id', $docId)->first();

        if ($model === null) {
            throw new NotFoundHttpException();
        }

        /* @var \Illuminate\Http\UploadedFile $file */
        $file = Input::file('file');

        $file = new \SplFileObject($file->path());
        $file->setFlags(\SplFileObject::READ_CSV);
        foreach ($file as $row) {
            if (!empty($row[0]) && !empty($row[1])) {
                $data = [
                    'doc_id' => $model->id,
                    'key' => trim($row[0]),
                    'value' => trim($row[1])
                ];
            }

            $this->addToDatabase($data);
        }

        return redirect()->route('admin_docs_view', ['id' => $docId]);

    }

    protected function addToDatabase(array $data)
    {
        try {
            DocsParams::forceCreate($data);
        } catch (\Exception $e) {

        }
    }

    /**
     * @param int $id
     * @param BenefitsRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function hide($id)
    {
        $model = DocsParams::where('id', $id)->first();

        if ($model !== null) {
            $model->visible = false;
            $model->save();
        }

        return redirect()->back();
    }

    /**
     * @param int $id
     * @param BenefitsRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        $model = DocsParams::where('id', $id)->first();

        if ($model !== null) {
            $model->visible = true;
            $model->save();
        }

        return redirect()->back();
    }
}




