<?php
namespace App\Http\Controllers\Admin;

use App\Entities\PrefixInterface;
use App\Repositories\ReviewsRepository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Imagine\Gd\Imagine;
use Illuminate\Routing\Controller;

class ReviewsController extends Controller implements PrefixInterface
{
    /**
     * @param ReviewsRepository $repository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ReviewsRepository $repository)
    {
        return view(
            'admin.reviews.index',
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
        return view('admin.reviews.add');
    }

    /**
     * @param int $id
     * @param ReviewsRepository $repository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id, ReviewsRepository $repository)
    {
        return view(
            'admin.reviews.edit',
            [
                'model' => $repository->find($id)
            ]
        );
    }

    /**
     * @param Request $request
     * @param ReviewsRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function insert(Request $request, ReviewsRepository $repository)
    {
        $data = array_map(
            'trim',
            $request->only(['name', 'content', 'url'])
        );

        $model = $repository->add($data);

        if (empty($model->id)) {
            throw new \RuntimeException();
        }

        return redirect()->route('admin_reviews_edit', ['id' => $model->id]);
    }

    /**
     * @param Request $request
     * @param ReviewsRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, ReviewsRepository $repository)
    {
        $id = $request->get('id');

        $data = array_map(
            'trim',
            $request->only(['priority', 'name', 'content', 'url'])
        );

        $repository->update($data, $id);

        return redirect()->route('admin_reviews_index');
    }

    /**
     * @param Request $request
     * @param Filesystem $filesystem
     * @param ReviewsRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function avatar(Request $request, Filesystem $filesystem, ReviewsRepository $repository)
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
        $model->avatar = $filename;
        $model->save();

        $directory = public_path('img') . DIRECTORY_SEPARATOR . self::PREFIX_REVIEWS;

        // создание директории
        if ($filesystem->exists($directory) !== true) {
            $filesystem->makeDirectory($directory, 0755, true);
        }

        $path = $directory . DIRECTORY_SEPARATOR . $filename;

        // сохраняем изображение
        $image = (new Imagine())->open($file->path());
        $image->save($path);

        return redirect()->route('admin_reviews_edit', ['id' => $model->id]);
    }

    /**
     * @param int $id
     * @param ReviewsRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function hide($id, ReviewsRepository $repository)
    {
        $repository->update(['visible' => false], $id);

        return redirect()->route('admin_reviews_index');
    }

    /**
     * @param int $id
     * @param ReviewsRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id, ReviewsRepository $repository)
    {
        $repository->update(['visible' => true], $id);

        return redirect()->route('admin_reviews_index');
    }
}
