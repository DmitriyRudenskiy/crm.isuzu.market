<?php
namespace App\Http\Controllers;

use App\Entities\Images;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;

class ImageController extends Controller
{

    public function index()
    {

        return view(
            'form.index',
            [
            ]
        );
    }

    public function upload(Request $request, Filesystem $filesystem)
    {
        $variantId = $request->get('id');

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
        $filename = md5(microtime() . $file->getClientOriginalName());

        // сохраняем путь к картинкеб получайем
        $model = new Images();
        $model->variant_id = (int)$variantId;
        $model->hash = $filename;
        $model->save();

        $directory = public_path('img/cover') . DIRECTORY_SEPARATOR;

        // создание директории
        if ($filesystem->exists($directory) !== true) {
            $filesystem->makeDirectory($directory, 0755, true);
        }

        // большая картинка
        $path = $directory . DIRECTORY_SEPARATOR . 'b_' . $filename . '.jpg';

        $imagine = new Imagine();
        $imagine->open($file->path())
            //->resize(new Box(770, 420))
            ->thumbnail(new Box(770, 420), ImageInterface::THUMBNAIL_OUTBOUND)
            ->save($path);

        // маленькая
        $path = $directory . DIRECTORY_SEPARATOR . 'm_' . $filename . '.jpg';
        $imagine = new Imagine();
        $imagine->open($file->path())
            ->thumbnail(new Box(128, 128), ImageInterface::THUMBNAIL_INSET)
            ->save($path);

        return redirect()->route('proposal_order', ['id' => $variantId]);
    }
}




