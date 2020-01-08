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
use Bookrr\Store\Models\Cart;
use Bookrr\Rates\Models\Rate;
use Bookrr\Stripe\Controllers\Cashier;




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
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController'
    ];

    public $formConfig = 'config_form.yaml';
    
    public $listConfig = 'config_list.yaml';

    public $relationConfig = 'config_relation.yaml';

    public $assetPath = '/plugins/bookrr/booking/assets/';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Bookrr.Booking', 'booking', 'parking');

        $this->model = new ParkingModel;

        $this->addCss($this->assetPath.'css/parking.css');
        
        $this->addJs($this->assetPath.'js/parking.js');

        $this->ProductListWidget = $this->ListWidget('config_list_product.yaml');

        $this->ProductToolbarWidget = $this->ToolbarWidget($this->ProductListWidget,'config_list_product.yaml');
    }

    public function onMoveKey()
    {
        $this->asExtension('FormController')->update(post('record_id'));
        $this->vars['recordId'] = post('record_id');
        return $this->makePartial('update_form');
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

        $this->model = $this->model->find($recordId);

        if($this->model->vehicle==NULL)
        {
            $this->fatalError = 'No Vehicle associated';
        }

        // Create Cart formWidget
        // $cartWidget = $this->FormWidget([
        //     'config'    => '$/bookrr/store/models/cart/fields.yaml',
        //     'alias'     => 'cartWidget',
        //     'model'     => $this->model->find($recordId)->cart,
        //     'arrayName' => 'cartWidget'
        // ]);

        // $this->vars['cartWidget'] = $cartWidget;

        return $this->asExtension('FormController')->update($recordId, $context);
    }

    public function onAddVehicleForm()
    {
        $this->asExtension('FormController')->update_onSave(post('record_id'));
        return $this->listRefresh();
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
        if(!Rate::amount())
        {
            throw new \ApplicationException('No rate is set!');
        }

        if(!$this->model->find(input('id'))->cart)
        {
            throw new \ApplicationException('No cart!');
        }

        $orders = $this->getOrders(input('id'));

        $this->vars['symbol'] = $orders['symbol'];
        
        $this->vars['orders'] = $orders['orders'];

        $this->vars['name'] = $orders['name'];

        $this->vars['email'] = $orders['email'];

        $this->vars['total'] = $orders['total'];

        return $this->makePartial('payment');
    }

    public function onCartForm($id)
    {
        $cart = $this->model->find($id)->cart;

        $parking = (object)$this->getParking($id);

        $product = (new \Bookrr\Store\Models\Product([
            'name' => $parking->name,
            'description' => $parking->quantity,
            'price' => $parking->total
        ]));

        $cart->products->prepend($product);

        $this->vars['cart'] = $cart;

        return $this->makePartial('cart/cart');
    }



    /*
    *   Overiders
    */
    public function listExtendQuery($query)
    {
        // List customer record
        if($this->user->isCustomer())
        {
            $query->where('user_id',$this->user->id);
        }
 
        return $query;
    }

    public function listExtendColumns($list)
    {
        if($this->user->isCustomer())
        {
            $list->removeColumn('name');
        }
    }

    public function formExtendFields($form)
    {
        # Field limitation for customer
        if($this->user->isCustomer())
        {
            $form->removeField('bay');
            $form->removeField('customer');
            $form->removeField('barcode');
            $form->removeField('status');
            $form->removeField('_movement');
            $form->removeField('products');
        }

        $form->fields['barcode']['disabled'] = true;
    }

    public function formBeforeCreate($model)
    {
        $alert = function($message=false){
            throw new ApplicationException($message ?  $message : 'No default vehicle for Customer!');
        };

        #
        # Attach the primary vehicle
        #
        if(!$this->user->isCustomer() AND $customer = Customers::find(post('Parking.customer')))
        {
            if(!$customer->vehicles()->hasDefault())
            {
                $alert();
            }
            
            $model->vehicle()->associate(
                $customer->getVehicle()
            );
        }
        else if($this->user->isCustomer())
        {
            if(!$this->user->vehicles()->hasDefault())
            {
                $alert("Please add a Vehicle first!");
            }

            $model->rules = [];
            $model->vehicle()->associate($this->user->customer->getVehicle());
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
        if($this->user->isCustomer())
        {
            $model->user_id = $this->user->id;
            $model->save();
        }

        #   Add Cart for Payments and Products
        $model->cart()->save(new Cart);

        return $model;
    }

    public function isProductPaid($product)
    {
        return true;
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

    public function getParking($id)
    {
        $book = $this->model->find($id);
        $rate = Rate::amount();

        if(!$book->date_in OR !$book->date_out)
        {
            throw new \ApplicationException('Date error!');
        }

        if($book->park_in AND $book->park_out)
        {
            $hours = Rate::getHours($book->park_in,$book->park_out);
        }
        else if($book->date_in AND $book->date_out)
        {
            $hours = Rate::getHours($book->date_in,$book->date_out);
        }

        return [
            "name"      => "Parking",
            "quantity"  => $hours."/Hrs",
            "price"     => number_format($rate,2),
            "total"     => $hours*round($rate,2)
        ];
    }

    public function getOrders($id)
    {
        $gateway = Cashier::config();

        $model = $this->model->find($id);

        $orders = ($model->cart) ? $model->cart->basket() : collect();

        // Append Parking Hours:Rate
        $orders->prepend( $this->getParking($id) );

        return [
            "orders"    => $orders,
            "currency"  => $gateway->currency,
            "symbol"    => $gateway->symbol,
            "name"      => $model->customer->user->first_name.' '.$model->customer->user->last_name,
            "email"     => $model->customer->user->email,
            "total"     => number_format($orders->pluck('total')->sum(), 2, '.', '')
        ];
    }
}