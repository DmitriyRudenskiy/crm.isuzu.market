<?php
namespace App\Http\Controllers\Admin;

use App\Repositories\DomainsRepository;
use App\Repositories\SitesRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SitesController extends Controller
{
    public function index(SitesRepository $repository)
    {
        return view(
            'admin.sites.index',
            [
                'list' => $repository->all()
            ]
        );
    }

    public function insert(Request $request, SitesRepository $repository)
    {
        $data = array_map(
            'trim',
            $request->only(['name', 'comment'])
        );

        $repository->create($data);

        return redirect()->route('admin_sites_index');
    }

    public function update(Request $request, SitesRepository $repository)
    {
        $id = $request->get('id');

        if ($id < 1) {
            throw new \RuntimeException();
        }

        $data = array_map(
            'trim',
            $request->only(['name', 'comment'])
        );

        $repository->update($data, $id);

        return redirect()->route('admin_sites_index');
    }
}
