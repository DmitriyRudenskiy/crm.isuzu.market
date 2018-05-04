<?php
namespace App\Http\Controllers\Front;

use App\Entities\Process\Copy;
use App\Entities\Process\Process;
use Illuminate\Routing\Controller;

class RegulationsController extends Controller
{
    public function index()
    {
        return view("front.regulations.index");
    }
}
