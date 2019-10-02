<?php namespace Bookrr\Booking\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Bookrr\Booking\Models\Ticket as TicketModel;
use Renatio\DynamicPDF\Classes\PDF;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;
use Faker\Factory as Faker;


/**
 * Ticket Back-end Controller
 */
class Ticket extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Bookrr.Booking', 'booking', 'ticket');
    }

    public static function generate($bookModel)
    {
        // Create Ticket Record
        $faker = Faker::create();
        $ticket = TicketModel::create([
            'qrcode'    => $faker->ean13,
            'barcode'   => $faker->ean13,
            'amount'    => '99.99'
        ]);
        $bookModel->clearRules()->ticket()->associate($ticket)->save();


        // Generate PDF
        $template = 'bookrr::pticket';
        $filename = 'ticket-'.uniqid().'.pdf';
        $data = [
            'qrcode'    => DNS2D::getBarcodePNG($bookModel->ticket->qrcode, "QRCODE"),
            'barcode'   => DNS1D::getBarcodePNG($bookModel->ticket->barcode, "EAN13", 1, 33)
        ];

        try {
            $pdf = PDF::loadTemplate( $template, $data)
            ->setOptions([
                'logOutputFile' => storage_path('temp/log.htm'),
                'isRemoteEnabled' => true,
            ]);

            \Storage::put('tickets/'.$filename, $pdf->output());

            return [
                'filename' => $filename,
                'url'  => asset('storage/app/tickets/'.$filename)
            ];

        } catch (ApplicationException $e) {
            $this->handleError($e);
        }
    }
}
