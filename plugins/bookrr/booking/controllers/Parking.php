<?php namespace Bookrr\Booking\Controllers;

use BackendMenu;
use BackendAuth as Auth;
use Request;
use Backend\Classes\Controller;
use October\Rain\Exception\ApplicationException;
use \Carbon\Carbon;
use Bookrr\User\Models\Customers;
use Bookrr\User\Models\Vehicle;
use Bookrr\Booking\Models\Parking as ParkingModel;
use Bookrr\Store\Models\Product;
use Bookrr\Bay\Models\Bay;
use Bookrr\Store\Controllers\Cart as CartController;
use Bookrr\Rates\Models\Rate;




class Parking extends CartController
{
    use \Bookrr\General\Traits\Widgets;

    public $model;

    public $ProductListWidget;

    public $ProductToolbarWidget;

    public $requiredPermissions = [
        'bookrr.booking.park'
    ];

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $assetPath = '/plugins/bookrr/booking/assets/';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Bookrr.Booking', 'booking', 'parking');

        $this->model = new ParkingModel;

        $this->addCss($this->assetPath.'css/pxpay.css');

        $this->addCss($this->assetPath.'css/parking.css');

        $this->addJs($this->assetPath.'js/pxpay.js');
        
        $this->addJs($this->assetPath.'js/parking.js');

        $this->ProductListWidget = $this->ListWidget('config_list_product.yaml');

        $this->ProductToolbarWidget = $this->ToolbarWidget($this->ProductListWidget,'config_list_product.yaml');
    }

    public function test($id)
    {
        dd(
            $this->model->find($id)
            ->customer
            ->user
            ->first_name
        );
    }

    public function index()
    {
        $this->addCss($this->assetPath.'css/parking.css');
        
        $this->addJs($this->assetPath.'js/parking.js');
        
        $this->pageTitle = 'Bookings';

        $this->asExtension('ListController')->index();
    }

    public function create()
    {
        return $this->asExtension('FormController')->create();
    }

    public function update($recordId, $context = null)
    {
        $this->addJs($this->assetPath.'js/parking.js');

        return $this->asExtension('FormController')->update($recordId, $context);
    }

    public function onCheckIn($id=null)
    {
        $validator = \Validator::make(post(),[
            'Parking.bay' => 'required'
        ]);

        if ($validator->fails()) {
            throw new \ValidationException($validator);
            return;
        }

        $id = $id ? : post('id');

        $model = $this->model->find($id);

        $this->vars['formModel'] = $model;

        if($model->setCheckIn())
        {
            \Flash::success('Successfully Check In!');

            if(post('context') != 'update')
            {
                if(post('id'))
                {
                    return $this->listRefresh();
                }
                else
                {
                    return \Redirect::to('/backend/bookrr/booking/parking/update/'.$id);
                }
            }
        }
        elseif($model->status == 'parked')
        {
            \Flash::warning('Already Check In!...');
        }

        $this->initForm($model);

        return [
            '#toolbar' => $this->makePartial('update_toolbar',$this->vars),
            '#Form-field-Parking-status-group' => $this->formGetWidget()->renderField('status',['useContainer' => true])
        ];
    }

    public function onCheckOut()
    {
        $model = $this->model->find(post('id'));

        $this->vars['formModel'] = $model;

        if($model->setCheckOut())
        {
            \Flash::success('Successfully Check Out!');

            if(post('context') != 'update')
            {
                if(post('id'))
                {
                    return $this->listRefresh();
                }
                else
                {
                    return \Redirect::to('/backend/bookrr/booking/parking/update/'.$id);
                }
            }

            return [
                '#toolbar' => $this->makePartial('update_toolbar',$this->vars)
            ];
        }
        elseif($model->status == 'checkout')
        {
            \Flash::warning('Already Check Out!...');
        }

        \Flash::error('Error Checking Out!...');
    }

    public function onPay()
    {
        $orders = $this->getOrders(post('id'));

        $orders->prepend((object)[
            "name"      => "Parking",
            "quantity"  => 1,
            "price"     => Rate::amount()
        ]);
        
        $this->vars['currency'] = \PxPay\PxPay::getSettings()->symbol;
        
        $this->vars['orders'] = $orders;

        $this->vars['total'] = number_format($orders->pluck('price')->sum(),2);

        return $this->makePartial('payment');
    }

    public function onPxpay()
    {
        $PxPay = new \PxPay\PxPay();

        $refNum = crc32(uniqid()).time();

        $PxPay->UrlFail = "{$PxPay->UrlFail}?param=refnum::".$refNum.",amount::".post('amount');

        $PxPay->UrlSuccess = "{$PxPay->UrlSuccess}?param=refnum::".$refNum.",amount::".post('amount');

        ParkingModel::findOrFail(post('id'))->update(['ref_num' => $refNum]);

        $PxPay->request([
            "amount"    => post('amount'),
            "reference" => $refNum
        ]);

        $this->vars['PxPay'] = $PxPay;

        return $this->makePartial('pxpay');
    }

    public function getOrders($id)
    {
        $orderIds = ParkingModel::find($id)->items;

        return Product::findMany($orderIds);
    }

    /*
    *   Overiders
    */
    public function formExtendFields($form)
    {
        # Field limitation for customer
        if($this->isCustomer())
        {
            $form->removeField('customer');
            $form->removeField('barcode');
            $form->removeField('status');
            $form->removeField('_movement');
        }
        
        $form->fields['barcode']['disabled'] = true;
    }

    public function formBeforeCreate($model)
    {
        #
        # Attach the primary vehicle
        #
        if($customer = Customers::find(post('Parking.customer')))
        {
            if(!$customer->vehicles()->hasDefault())
            {
                throw new ApplicationException('No default vehicle for Customer!');
            }
            
            $model->vehicle()->associate(
                $customer->vehicles->where('primary',1)->first()
            );
        }
        else if($this->isCustomer())
        {
            $model->rules = [];
            $vehicle = $this->getCustomer()->vehicles->where('primary',1)->first();
            $model->vehicle()->associate($vehicle);
        }

        return $model;
    }

    public function formBeforeSave($model)
    {
        return $model;
    }

    public function formAfterCreate($model)
    {
        #
        #   Booking should belongs to customer
        #
        if($this->isCustomer())
        {
            $model->user_id = $this->getCustomerId();
            $model->save();
        }

        return $model;
    }

    public function listExtendQuery($query)
    {
        // List customer record
        // if($this->user->isCustomer())
        // {
        //     if($this->user->aeroUser)
        //     {
        //         $query->where('user_id',$this->user->aeroUser->id);
        //     }
        //     else
        //     {
        //         $query->where('user_id',0);
        //     }
        // }

        return $query;
    }

    
    /*
    *   Helper
    */
    public function listFormatDate($value)
    {
        $dateTime   = (new Carbon())->parse($value)->format('d/m/y (h:i A)');
        $diffHuman  = (new Carbon($value))->diffForHumans();
        $class      = str_contains($diffHuman,'ago') ? 'default' : 'primary';
        return [
            $dateTime,
            $diffHuman,
            $class
        ];
    }

    /*
    *   PROTECTED
    */
    protected function getCustomer()
    {
        if($this->isCustomer())
            return AeroUser::auth()->user;

        return null;
    }

    protected function isCustomer()
    {
        return strtolower($this->user->role->name)=='customer';
    }

    protected function getCustomerId()
    {
        if($this->isCustomer())
            return $this->getCustomer()->id;

        return null;
    }
}
