<?php
namespace App\Http\Controllers\Front;

use App\Entities\Process\Copy;
use App\Entities\Process\Process;
use App\Entities\Regulations;
use Illuminate\Routing\Controller;

class RegulationsController extends Controller
{
    public function index(Regulations $regulationsRepository)
    {
        $list = $regulationsRepository->all();
        return view("front.regulations.index", ["list" => $list]);
    }
}
