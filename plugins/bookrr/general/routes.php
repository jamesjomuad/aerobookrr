<?php

use Backend\Models\User as BackUser;
use Backend\Models\UserRole;
use Aeroparks\User\Models\BaseUser;
use Aeroparks\User\Models\User;
use Aeroparks\User\Models\Staff;
use Aeroparks\User\Models\Customer;
use Aeroparks\User\Models\Vehicle;
use Aeroparks\Booking\Models\Parking;
use Aeroparks\Booking\Models\Movement;
use Aeroparks\Booking\Models\Ticket;
use Aeroparks\Booking\Controllers\Ticket as TicketController;
use Aeroparks\Bay\Models\Bay;
use Renatio\DynamicPDF\Classes\PDF;
use Faker\Factory as Faker;
use Carbon\Carbon;
use Kocholes\BarcodeGenerator\Classes\BarcodeManager;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;


/*
Route::get('barcode',function(){
    $barcode = "09950961902";
    $qrcode = "zxcvbnm";
    dump('<img src="data:image/png;base64,' . DNS2D::getBarcodePNG($qrcode, "QRCODE") . '"/>');
    echo '<img src="data:image/png;base64,' . DNS2D::getBarcodePNG($qrcode, "QRCODE") . '" alt="barcode" style="width:10%;"/>';
    echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($barcode, "EAN13", 1, 33) . '" alt="barcode" style="width:10%;"/>';
});

Route::get('pticket',function(){
    $booking = Parking::find(15);
    $ticket = Ticket::find(51);


    $template = 'aeroparks::pticket';
    $filename = 'ticket-'.str_random(40).'.pdf';
    $data = [
        'qrcode' => '<img src="data:image/png;base64,' . DNS2D::getBarcodePNG($ticket->qrcode, "QRCODE") . '" style="width:70px;float:left"/>',
        'barcode' => '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($ticket->barcode, "EAN13", 1, 33) . '" style="width:200px;float:right"/>'
    ];


    try {

        $pdf = PDF::loadTemplate( $template, $data)
        ->setOptions([
            'logOutputFile' => storage_path('temp/log.htm'),
            'isRemoteEnabled' => true,
        ]);

        return $pdf->stream($filename);


    } catch (ApplicationException $e) {
        $this->handleError($e);
    }
});
*/



