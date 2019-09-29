<?php namespace Bookrr\Booking\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Bookrr\Booking\Models\Parking;

/**
 * Calendar Back-end Controller
 */
class Calendar extends Controller
{
    protected $assetsPath = '/plugins/aeroparks/booking/assets';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Aeroparks.Booking', 'booking', 'calendar');
    }

    public function index()
    {
        $this->pageTitle = "Booking Calendar";
        $this->addCss($this->assetsPath.'/css/fullcalendar.css');
        $this->addCss($this->assetsPath.'/css/style.css');
        $this->addJs($this->assetsPath.'/js/fullcalendar.min.js');
        $this->addJs($this->assetsPath.'/js/script.js','v1.5');
    }

    public function bookings()
    {

        if($this->user->isCustomer() AND (bool) $this->user->aeroUser->bookings)
        {
            $Booking = $this->user->aeroUser->bookings;
        }
        else
        {
            $Booking = Parking::getBookings()->get();
        }

        $result = $Booking
        ->map(function($model){
            if($model->user)
            return [
                'title' => '('.$model->id.') '.$model->user->backendUser->first_name.' '.$model->user->backendUser->last_name,
                'start' => $model->date_in,
                // 'end'   => $model->date_out,
                'url'   => '/backend/aeroparks/booking/parking/update/'.$model->id,
                'backgroundColor' => '#ff6600',
                'borderColor' => '#ff5500'
            ];
        })->toArray();

        return \Response::json($result);
    }
    
}
