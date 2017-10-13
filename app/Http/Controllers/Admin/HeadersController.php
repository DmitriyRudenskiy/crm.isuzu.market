<?php
namespace App\Http\Controllers\Admin;

use App\Entities\PrefixInterface;
use App\Repositories\HeadersRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;
use Illuminate\Filesystem\Filesystem;
use Imagine\Gd\Imagine;

class HeadersController extends Controller implements PrefixInterface
{
    /**
     * @param HeadersRepository $repository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(HeadersRepository $repository)
    {
        return view(
            'admin.headers.index',
            [
                'list' => $repository->getList()
            ]
        );
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add()
    {
        return view('admin.headers.add');
    }

    /**
     * @param int $id
     * @param HeadersRepository $repository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id, HeadersRepository $repository)
    {
        return view(
            'admin.headers.edit',
            [
                'model' => $repository->find($id)
            ]
        );
    }

    /**
     * @param Request $request
     * @param HeadersRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function insert(Request $request, HeadersRepository $repository)
    {
        $data = array_map(
            'trim',
            $request->only(
                [
                    'title',
                    'sub_title',
                    'description',
                    'button',
                    'add_1',
                    'add_2',
                    'add_3',
                    'button_url'
                ]
            )
        );

        $model = $repository->add($data);

        if (empty($model->id)) {
            throw new \RuntimeException();
        }

        return redirect()->route('admin_headers_edit', ['id' => $model->id]);
    }

    /**
     * @param Request $request
     * @param HeadersRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, HeadersRepository $repository)
    {
        $id = $request->get('id');

        $data = array_map(
            'trim',
            $request->only(
                [
                    'title',
                    'sub_title',
                    'description',
                    'button',
                    'add_1',
                    'add_2',
                    'add_3',
                    'button_url'
                ]
            )
        );

        $repository->update($data, $id);

        return redirect()->route('admin_headers_index');
    }

    /**
     * Загружаем изображение для приимуществ
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bg(Request $request, Filesystem $filesystem, HeadersRepository $repository)
    {
        $id = $request->get('id');
        $value = $request->get('value');

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
        $model->$value = $filename;
        $model->save();

        $directory = public_path('img') . DIRECTORY_SEPARATOR . self::PREFIX_HEADERS;

        // создание директории
        if ($filesystem->exists($directory) !== true) {
            $filesystem->makeDirectory($directory, 0755, true);
        }

        $path = $directory . DIRECTORY_SEPARATOR . $filename;

        // сохраняем изображение
        $image = (new Imagine())->open($file->path());
        $image->save($path);

        return redirect()->route('admin_headers_edit', ['id' => $model->id]);
    }

    public function show($id, HeadersRepository $repository)
    {
        $repository->show($id);

        return redirect()->route('admin_headers_index');
    }
}
