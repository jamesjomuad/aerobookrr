<?php namespace Bookrr\Store\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Bookrr\Store\Models\Cart as CartModel;
use Bookrr\Store\Models\Product;


class Cart extends Controller
{

    public $model;
    public $currency;
    public $items = 0;
    public $totalQty = 0;
    public $totalPrice = 0;
    public $assetPath = '/plugins/bookrr/store/assets/';

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Bookrr.Store', 'store', 'cart');

        $this->model = new CartModel;

        $this->currency = \PxPay\PxPay::getSettings()->symbol;
    }

    public function formBeforeSave($model)
    {
        return $model;
    }

    public function getCart($id=null)
    {
        if($this->params && $Cart = $this->model->find($id ? : $this->params[0])->cart)
        {
            return $Cart;
        }

        return new CartModel();
    }

    public function getProducts()
    {
        if($Cart = $this->getCart())
        {
            return $Cart->products;
        }
        return null;
    }

    public function getTfoot($cart=null)
    {
        $cart = $cart ? : $this->getCart();

        return [
            'total'            => $cart->getTotalPrice(),
            'total_quantity'   => $cart->products->sum('pivot.quantity'),
            'cash_tender'      => 0,
            'cash_change'      => 0,
            'discount_percent' => '0%',
            'discount_amount'  => '$0'
        ];
    }


    #
    #  Event Handlers
    #
    public function onProductList()
    {
        $this->vars['ListWidget'] = $this->ProductListWidget;

        $this->vars['Toolbar'] =  $this->ProductToolbarWidget;

        return $this->makePartial($this->getCartViewPath('product_lists'));
    }

    public function onAddCartItem($recordId)
    {
        $cart = $this->getCart();

        try {
            $Booking = $this->model->find($recordId);

            $Product = Product::find(post('id'));
            
            if($Booking->cart == null)
            {
                $Booking->cart()->save($cart);
            }

            $cart->products()->attach($Product,['quantity'=>1]);

        } catch (\Illuminate\Database\QueryException $exception) {
            
            $pivot = $cart->products->find(post('id'))->pivot;

            $pivot->quantity += 1;

            $pivot->save();

            \Flash::success($Product->name . ' quantity updated!');

            return $this->onRefreshCartItem($cart);
        }
        
        \Flash::success($Product->name . ' added!');
        
        $this->vars['product'] = $Product;

        return $this->onRefreshCartItem();
    }

    public function onDeleteCartItem($recordId)
    {
        $cart = $this->model->find($recordId)->cart;

        $cart->products()->detach(post('Cart.checked'));

        return $this->onRefreshCartItem($cart);
    }

    public function onRefreshCartItem($cart = null)
    {
        $Cart = $cart ? : $this->getCart();

        $Products = $Cart->products;

        $this->vars['products'] = $Products;

        $this->vars['details'] = $this->getTfoot($cart);

        return [
            '#cart-list' => $this->makePartial($this->getCartViewPath('cart_items')),
            '#cart-footer' => $this->makePartial($this->getCartViewPath('cart_footer'))
        ];
    }

    public function getCartViewPath($partial='')
    {
        return plugins_path()."/bookrr/store/controllers/cart/$partial";
    }

    public function onUpdateQty($pid=null,$qty=null)
    {
        $cart = $this->getCart();

        $pid = post('pid') ? : $pid;

        $qty = post('Cart.qty.'.$pid) ? : $qty;

        $pivot = $cart->products->find($pid)->pivot;

        $pivot->quantity = $qty;

        if($pivot->save())
        {
            return $this->onRefreshCartItem($cart);
        }
    }

}
