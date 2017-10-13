<?php
namespace App\Http\Controllers\Admin;


use App\Repositories\VideoRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class VideoController extends Controller
{
    /**
     * @param VideoRepository $repository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(VideoRepository $repository)
    {
        return view(
            'admin.video.index',
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
        return view('admin.video.add');
    }

    /**
     * @param int $id
     * @param VideoRepository $repository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id, VideoRepository $repository)
    {
        return view(
            'admin.video.edit',
            [
                'model' => $repository->find($id)
            ]
        );
    }

    /**
     * @param Request $request
     * @param VideoRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function insert(Request $request, VideoRepository $repository)
    {
        $data = array_map(
            'trim',
            $request->only(['url', 'title', 'description'])
        );

        $model = $repository->add($data);

        if (empty($model->id)) {
            throw new \RuntimeException();
        }

        return redirect()->route('admin_video_edit', ['id' => $model->id]);
    }

    /**
     * @param Request $request
     * @param VideoRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, VideoRepository $repository)
    {
        $id = $request->get('id');

        $data = array_map(
            'trim',
            $request->only(['url', 'title', 'description'])
        );

        $repository->update($data, $id);

        return redirect()->route('admin_video_index');
    }

    /**
     * @param int $id
     * @param VideoRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function hide($id, VideoRepository $repository)
    {
        $repository->update(['visible' => false], $id);

        return redirect()->route('admin_video_index');
    }

    /**
     * @param int $id
     * @param VideoRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id, VideoRepository $repository)
    {
        $repository->update(['visible' => true], $id);

        return redirect()->route('admin_video_index');
    }
}
