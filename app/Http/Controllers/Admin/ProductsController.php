<?php
namespace App\Http\Controllers\Admin;

use App\Entities\PrefixInterface;
use App\Repositories\LabelRepository;
use App\Repositories\ProductsRepository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Imagine\Gd\Imagine;

class ProductsController extends Controller implements PrefixInterface
{
    /**
     * @param ProductsRepository $productRepository
     * @param LabelRepository $labelRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ProductsRepository $productRepository, LabelRepository $labelRepository)
    {
        return view(
            'admin.products.index',
            [
                'list' => $productRepository->getListAll(),
                'label' => $labelRepository->getAllList()
            ]
        );
    }

    /**
     * @param LabelRepository $labelRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add(LabelRepository $labelRepository)
    {
        return view(
            'admin.products.add',
            [
                'model' => [],
                'label' => $labelRepository->getAllList()
            ]
        );
    }

    /**
     * @param int $id
     * @param ProductsRepository $productRepository
     * @param LabelRepository $labelRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id, ProductsRepository $productRepository, LabelRepository $labelRepository)
    {
        return view(
            'admin.products.edit',
            [
                'model' => $productRepository->find($id),
                'label' => $labelRepository->getAllList()
            ]
        );
    }

    /**
     * @param Request $request
     * @param ProductsRepository $productRepository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function insert(Request $request, ProductsRepository $repository)
    {
        $data = array_map(
            'trim',
            $request->only(
                [
                    'equipment',
                    'engine',
                    'power',
                    'transmission',
                    'drive_unit',
                    'body_type',
                    'colour',
                    'button'
                ]
            )
        );

        $model = $repository->add($data);

        if (empty($model->id)) {
            throw new \RuntimeException();
        }

        return redirect()->route('admin_products_edit', ['id' => $model->id]);
    }

    /**
     * @param Request $request
     * @param ProductsRepository $productRepository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, ProductsRepository $repository)
    {
        $id = $request->get('id');

        $data = array_map(
            'trim',
            $request->only(
                [
                    'priority',
                    'equipment',
                    'engine',
                    'power',
                    'transmission',
                    'drive_unit',
                    'body_type',
                    'colour',
                    'button'
                ]
            )
        );

        $repository->update($data, $id);

        return redirect()->route('admin_products_index');
    }

    /**
     * Загружаем изображение для приимуществ
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function photo(Request $request, Filesystem $filesystem, ProductsRepository $repository)
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
        $model->photo = $filename;
        $model->save();

        $directory = public_path('img') . DIRECTORY_SEPARATOR . self::PREFIX_PRODUCTS;

        // создание директории
        if ($filesystem->exists($directory) !== true) {
            $filesystem->makeDirectory($directory, 0755, true);
        }

        $path = $directory . DIRECTORY_SEPARATOR . $filename;

        // сохраняем изображение
        $image = (new Imagine())->open($file->path());
        $image->save($path);

        return redirect()->route('admin_products_edit', ['id' => $model->id]);
    }

    /**
     * @param int $id
     * @param ProductsRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function hide($id, ProductsRepository $repository)
    {
        $repository->update(['visible' => false], $id);

        return redirect()->route('admin_products_index');
    }

    /**
     * @param int $id
     * @param ProductsRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id, ProductsRepository $repository)
    {
        $repository->update(['visible' => true], $id);

        return redirect()->route('admin_products_index');
    }
}
