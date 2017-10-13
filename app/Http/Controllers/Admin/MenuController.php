<?php
namespace App\Http\Controllers\Admin;

use App\Entities\PrefixInterface;
use App\Repositories\MenuRepository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Imagine\Gd\Imagine;
use Illuminate\Routing\Controller;

class MenuController extends Controller implements PrefixInterface
{
    /**
     * @param MenuRepository $repository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(MenuRepository $repository)
    {
        return view(
            'admin.menu.index',
            [
                'logo' => $repository->getLogo(),
                'phone' => $repository->getPhone(),
                'list' => $repository->getItems()
            ]
        );
    }

    /**
     * @param Request $request
     * @param MenuRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function insert(Request $request, MenuRepository $repository)
    {
        $data = array_map(
            'trim',
            $request->only(['priority', 'title', 'url'])
        );

        $repository->add($data);

        return redirect()->route('admin_menu_index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function phone(Request $request, MenuRepository $repository)
    {
        $phone = trim($request->get('phone'));
        $model = $repository->getPhone();
        $repository->update(['title' => $phone], $model->id);

        return redirect()->route('admin_menu_index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, MenuRepository $repository)
    {
        $id = $request->get('id');

        if ($id > 0) {
            $data = array_map(
                'trim',
                $request->only(['priority', 'title', 'url'])
            );

            $repository->update($data, $id);
        }

        return redirect()->route('admin_menu_index');
    }

    public function logo(Filesystem $filesystem, MenuRepository $repository)
    {
        $model = $repository->getLogo();

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
        $model->title = $filename;
        $model->save();

        $directory = public_path('img') . DIRECTORY_SEPARATOR . self::PREFIX_MENU_LOGO;

        // создание директории
        if ($filesystem->exists($directory) !== true) {
            $filesystem->makeDirectory($directory, 0755, true);
        }

        $path = $directory . DIRECTORY_SEPARATOR . $filename;

        // сохраняем изображение
        $image = (new Imagine())->open($file->path());
        $image->save($path);

        return redirect()->route('admin_menu_index');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove($id, MenuRepository $repository)
    {
        $repository->delete($id);

        return redirect()->route('admin_menu_index');
    }
}
