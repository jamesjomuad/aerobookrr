<?php namespace Bookrr\Booking\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Carbon\Carbon;
use Flash;
use Bookrr\Booking\Models\Parking;
use Bookrr\Booking\Models\Movement as MovementModel;
use Bookrr\User\Models\Vehicle;




class Movement extends Controller
{
    public $requiredPermissions = [
        'aeroparks.movement'
    ];

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    protected $assetsPath = '/plugins/aeroparks/booking/assets';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Aeroparks.Booking', 'move_key');
    }

    public function index()
    {
        $this->pageTitle = 'Key Movement';

        $this->addJs($this->assetsPath.'/js/movement.js','v2.0.0');

        $this->asExtension('ListController')->index();
    }

    public function create($id=null)
    {
        $this->booking_id = $id;
        
        $this->pageTitle = 'Create Key Movement';

        $this->asExtension('FormController')->create();
    }

    public function onActionForm()
    {
        if($booking = Parking::where('barcode',post('barcode'))->first())
        {
            if($booking->customer)
            {
                $user = $booking->customer->backendUser;

                $customer = $booking->customer;

                $this->vars = [
                    'booking'   => $booking,
                    'user'      => $user,
                    'customer'  => $customer,
                    'number'    => $booking->number,
                    'name'      => $user->first_name.' '.$user->last_name,
                    'email'     => $user->email,
                    'phone'     => $customer->phone
                ];

                return $this->makePartial('action');
            }
            else
            {
                Flash::error('Record has no Customer!');
            }

        }

        Flash::error('Record not found!');
    }

    public function listFormatDate($value)
    {
        $dateTime   = (new Carbon())->parse($value)->format('l, M d, Y (H:i A)');
        $diffHuman  = (new Carbon($value))->diffForHumans();
        $class      = str_contains($diffHuman,'ago') ? 'default' : 'primary';
        return [
            $dateTime,
            $diffHuman,
            $class
        ];
    }



    /*
    *   Method Overide
    */
    public function formAfterSave($model)
    {
        if(post('Movement._bay'))
        {
            $model->booking->setBay(post('Movement._bay'));
        }
        
        $model->booking->save();
        
        return $model;
    }

    public function formExtendModel($model)
    {
        if(@$this->params[0])
        {
            $model->booking_id = $this->params[0];
        }
    }

}