<?php
namespace App\Http\Controllers\Admin;

use App\Entities\PrefixInterface;
use App\Repositories\BenefitsRepository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Imagine\Gd\Imagine;
use Illuminate\Routing\Controller;

class BenefitsController extends Controller implements PrefixInterface
{
    /**
     * @param BenefitsRepository $repository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(BenefitsRepository $repository)
    {
        return view(
            'admin.benefits.index',
            [
                'list' => $repository->getAllList()
            ]
        );
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add()
    {
        return view('admin.benefits.add');
    }

    /**
     * @param $id
     * @param BenefitsRepository $repository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id, BenefitsRepository $repository)
    {
        return view(
            'admin.benefits.edit',
            [
                'model' => $repository->find($id)
            ]
        );
    }

    public function insert(Request $request, BenefitsRepository $repository)
    {
        $data = array_map(
            'trim',
            $request->only(['title', 'description'])
        );

        $model =$repository->add($data);

        if (!empty($model->id) < 1) {
            throw new \RuntimeException();
        }

        return redirect()->route('admin_benefits_edit', ['id' => $model->id]);
    }

    public function update(Request $request, BenefitsRepository $repository)
    {
        $id = $request->get('id');

        $data = array_map(
            'trim',
            $request->only(['priority', 'title', 'description'])
        );

        $repository->update($data, $id);

        return redirect()->route('admin_benefits_index');
    }

    /**
     * Загружаем изображение для приимуществ
     *
     * @param Request $request
     * @param Filesystem $filesystem
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cover(Request $request, Filesystem $filesystem, BenefitsRepository $repository)
    {
        $id = $request->get('id');
        $model = $repository->find($id);

        /* @var \Illuminate\Http\UploadedFile $file */
        $file = Input::file('image');

        $validator = Validator::make(
            ['image' => $file],
            ['image' => 'image']
        );

        if ($validator->fails()) {
            throw new \RuntimeException();
        }

        // имя файла
        $filename = md5(microtime() . $model) . "." . $file->getClientOriginalExtension();

        // сохраняем путь к картинке
        $model->cover = $filename;
        $model->save();

        $directory = public_path('img') . DIRECTORY_SEPARATOR . self::PREFIX_BENEFITS;

        // создание директории
        if ($filesystem->exists($directory) !== true) {
            $filesystem->makeDirectory($directory, 0755, true);
        }

        $path = $directory . DIRECTORY_SEPARATOR . $filename;

        // сохраняем изображение
        $image = (new Imagine())->open($file->path());
        $image->save($path);

        return redirect()->route('admin_benefits_edit', ['id' => $model->id]);
    }

    /**
     * @param int $id
     * @param BenefitsRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function hide($id, BenefitsRepository $repository)
    {
        $repository->update(['visible' => false], $id);

        return redirect()->route('admin_benefits_index');
    }

    /**
     * @param int $id
     * @param BenefitsRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id, BenefitsRepository $repository)
    {
        $repository->update(['visible' => true], $id);

        return redirect()->route('admin_benefits_index');
    }
}
