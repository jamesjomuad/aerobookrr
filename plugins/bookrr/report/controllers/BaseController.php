<?php namespace Bookrr\Report\Controllers;

use Backend\Classes\Controller;
use \Carbon\Carbon;
use Renatio\DynamicPDF\Classes\PDF;
use Bookrr\Booking\Models\Parking;






class BaseController extends Controller
{

    public $pdfTemplate = 'bookrr::reports';
    public $models;

    
    public function __construct()
    {
        parent::__construct();

        $this->models['booking'] = new Parking;

        $this->models = (object) $this->models;
    }

    public function debug($v)
    {
        $this->vars['v'] = $v;
        return $this->makePartial('debug');
    }

    public function createFormWidget($options)
    {
        $config = $this->makeConfig($options['config']);

        $config->model = $options['model'];

        $config->alias = $options['alias'];

        $config->arrayName = $options['arrayName'];

        $widget = $this->makeWidget('Backend\Widgets\Form', $config);

        $widget->bindToController();

        return $widget;
    }   

    public function generateFilename()
    {
        return 'reports-'.str_random(40).'.pdf';
    }

    public function createPdf($options)
    {
        $config = [
            'data' => $options['data'],
        ];

        $filename = $this->generateFilename();

        try {
            $pdf = PDF::loadTemplate( $this->pdfTemplate,[ 'table' => $config['data'] ])
            ->setOptions([
                'logOutputFile' => storage_path('temp/log.htm'),
                'isRemoteEnabled' => true,
            ]);

            \Storage::put('uploads/'.$filename, $pdf->output());

            $path = asset('storage/app/uploads/'.$filename);

            return [
                'filename' => $filename,
                'url'  => $path,
                'path' => storage_path('app/uploads/'.$filename)
            ];

        } catch (ApplicationException $e) {
            $this->handleError($e);
        }
    }

    public function onPdf()
    {
        $table = post('table');

        $this->vars['pdfUrl'] = $this->createPdf(['data'=>$table])['url'];

        return $this->makePartial('~/plugins/bookrr/report/controllers/base/_pdf.htm');
    }

    public function onDownload()
    {
        $table = post('table');

        $pdf = $this->createPdf(['data'=>$table]);

        return \Redirect::to('backend/bookrr/report/booking/download/'.$pdf['filename']);
    }

    public function download($filename)
    {
        $pathToFile = storage_path('app/uploads/'.$filename);

        return \Response::download($pathToFile, $filename, [
            'HTTP/1.1 200 OK',
            'Pragma: public',
            'Content-Type: application/pdf'
        ]);
    }

}
