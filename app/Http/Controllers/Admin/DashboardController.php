<?php
namespace App\Http\Controllers\Admin;

use App\Repositories\SitesRepository;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    public function index(SitesRepository $repository)
    {
        $model = $repository->find(env('APP_DOMAIN_ID'));

        return view(
            'admin.dashboard.index',
            [
                'model' => $model
            ]
        );
    }

    public function info()
    {
        return view('admin.dashboard.info');
    }
}
