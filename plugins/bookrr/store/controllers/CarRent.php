<?php namespace Aeroparks\Store\Controllers;

use BackendMenu;
use BackendAuth as Auth;
use Validator;
use ValidationException;
use Backend\Classes\Controller;
use Aeroparks\Store\Models\CarRent as Car;
use Aeroparks\Store\Models\CarBooking as CarBooking;
use Flash;
use \Carbon\Carbon;


class CarRent extends Controller
{

    use \Aeroparks\Store\Traits\Widgets;

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config/form.yaml';
    public $listConfig = [
        'vehicle'  => 'config/vehicle_list.yaml',
        'booking'   => 'config/booking_list.yaml'
    ];

    protected $bookFormWidget;

    public function __construct()
    {
        if(Auth::check() AND Auth::getUser()->isCustomer())
        {
            $this->listConfig = 'config/list_customer.yaml';
        }
        
        parent::__construct();

        if(Auth::check() AND $this->user->isCustomer())
        {
            BackendMenu::setContext('Aeroparks.Store', 'car-rental');
        }
        else
        {
            BackendMenu::setContext('Aeroparks.Store', 'store', 'car-rental');
        }

        $this->addCss('/plugins/aeroparks/store/assets/fotorama.css');
        $this->addCss('/plugins/aeroparks/store/assets/style.css');
        $this->addJs('/plugins/aeroparks/store/assets/fotorama.js');
        $this->addJs('/plugins/aeroparks/store/assets/script.js');

        
    }

    public function index()
    {
        $this->vars['overview']['booking'] = $this->getTodayBookings();

        $this->vars['overview']['cancelled'] = $this->getCancelledBookings();

        $this->vars['overview']['travelling'] = $this->getOnTripBookings();
        
        $this->vars['overview']['available'] = $this->getAvailableCars();

        $this->asExtension('ListController')->index();
    }

    public function booking($id)
    {
        // dd(
        //     // $this
        //     $this->asExtension('FormController')
        // );
    }

    public function onRentForm()    
    {
        $this->bookFormWidget = $this->createFormWidget([
            'alias'     => 'rentForm',
            'arrayName' => 'Rental',
            'model'     => new Car,
            'config'    => '$/aeroparks/store/models/carrentbooking/customer_fields.yaml'
        ]);
        
        $this->vars['model'] = Car::find(post('id'));

        $this->vars['bookFormWidget'] = $this->bookFormWidget;

        return $this->makePartial('booking_form');
    }

    public function onBookNow()
    {
        $number = '#'.str_random(5).' - '.strftime("%Y%m%d%H%M");

        $validation = Validator::make(
            post(),
            ['Rental.date_in' => 'required'],
            ['Rental.date_in.required' => 'Booking Date is required']
        );

        if ($validation->fails())
        {
            throw new ValidationException($validation);
        }

        $booking = new CarBooking();
        
        $booking->user_id = $this->user->id;

        $booking->car_id = post('car_id');

        $booking->date_in = (new Carbon())->parse(post('Rental.date_in'))->format('Y-m-d H:i:s');

        $booking->date_out = (new Carbon())->parse(post('Rental.date_out'))->format('Y-m-d H:i:s');

        $booking->number = $number;

        $booking->passenger = post('Rental.passenger');

        $booking->phone = post('Rental.phone');

        if($booking->save())
        {
            \Flash::success('Booking Successful!');
            return;
        }

        \Flash::error('Booking Failed!');
    }

    public function getCancelledBookings()
    {
        return CarBooking::where('status','=','cancelled')->count();
    }

    public function getOnTripBookings()
    {
        return CarBooking::where('status','=','travelling')->count();
    }

    public function getTodayBookings()
    {
        return CarBooking::whereDate('date_in',Carbon::today())->count();
    }

    public function getAvailableCars()
    {
        return Car::where('status','=','available')->count();
    }

}
