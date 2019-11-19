<?php namespace Bookrr\Booking\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Bookrr\Booking\Models\Parking;
use Carbon\Carbon;

/**
 * Calendar Back-end Controller
 */
class Calendar extends Controller
{
    protected $assetsPath = '/plugins/bookrr/booking/assets';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Bookrr.Booking', 'booking', 'calendar');
    }

    public function index()
    {
        $this->pageTitle = "Booking Calendar";
        $this->addCss($this->assetsPath.'/css/fullcalendar.css');
        $this->addCss($this->assetsPath.'/css/style.css');
        $this->addJs($this->assetsPath.'/js/fullcalendar.min.js');
        $this->addJs($this->assetsPath.'/js/script.js','v1.6');
    }

    public function bookings()
    {
        $timestamp = Carbon::createFromTimestamp(input('time'));

        if($this->user->isCustomer() AND (bool) $this->user->customer)
        {
            $Booking = $this->user->customer->bookings;
        }
        else
        {
            $Booking = Parking::monthOf($timestamp);
        }

        $result = $Booking->get()->map(function($model){
            if($model->customer)
            return [
                'title' => '('.$model->id.') '.$model->customer->user->first_name.' '.$model->customer->user->last_name,
                'start' => $model->date_in,
                'url'   => '/backend/bookrr/booking/parking/update/'.$model->id,
                'backgroundColor' => '#ff6600',
                'borderColor' => '#ff5500'
            ];
        });

        return response()->json($result->toArray());
    }
    
}
