<?php
namespace App\Http\Controllers;

use App\Entities\Docs;
use App\Repositories\ImagesRepository;
use App\Repositories\VariantsRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

class TestController extends Controller
{
    /**
     * @param VariantsRepository $variantsRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(VariantsRepository $variantsRepository)
    {
        $list = $variantsRepository->getList();

        return view(
            'form.index',
            [
                'list' => $list
            ]
        );
    }

    /**
     * @param VariantsRepository $variantsRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function order(Request $request,
                          VariantsRepository $variantsRepository,
                          ImagesRepository $imagesRepository)
    {
        $variantId = $request->get('id');
        $variant = $variantsRepository->find($variantId);
        $images = $imagesRepository->getList($variantId);

        $docs = Docs::where('visible', true)->get();

        return view(
            'form.order',
            [
                'variant' => $variant,
                'images' => $images,
                'docs' => $docs
            ]
        );
    }

    public function download(Request $request,
                             VariantsRepository $variantsRepository,
                             ImagesRepository $imagesRepository)
    {
        $variant = $variantsRepository->find($request->get("variant_id"));
        $image = $imagesRepository->find($request->get("image_id"));
        $docs = [];
        $docId = (int)$request->get("doc_id");

        if ($docId > 0) {

            $model = Docs::where('id', $docId)->first();

            if ($model === null) {
                throw new NotFoundHttpException();
            }

            $docs = $model->getParams()->where('visible', true)->get();
        }

        // dd($docs);

        $dataFile = storage_path('params')
            . DIRECTORY_SEPARATOR
            . $variant->id
            . '.csv';

        if (!file_exists($dataFile)) {
            throw new \RuntimeException();
        }

        $csv = new \SplFileObject($dataFile);
        $csv->setFlags(\SplFileObject::READ_CSV);

        $imageFile = public_path('img/cover')
            . DIRECTORY_SEPARATOR
            . 'b_'
            . $image->hash
            . '.jpg';

        //dd(file_exists($imageFile));

        // name output file
        $filename = 'test_' . time() . '.pdf';

        // render data
        $data = [
            "public_path" => public_path('img'),
            "image_file" => $imageFile,
            "name" => $variant->getFullTitle(),
            "price" => (int)$request->get("price"),
            "price_bonus" => (int)$request->get("price_bonus"),
            "params" => $csv,
            "docs" => $docs
        ];

        /* @var Pdf $pdf */
        $pdf = PDF::loadView('pdf.first', $data);

        // add new page
        $html = view('pdf.second', $data)->render();

        $pdf->mpdf->AddPage();
        $pdf->mpdf->WriteHTML($html);

        // add new page
        $html = view('pdf.page3', $data)->render();

        $pdf->mpdf->AddPage();
        $pdf->mpdf->WriteHTML($html);

        return $pdf->download($filename);
    }
}




