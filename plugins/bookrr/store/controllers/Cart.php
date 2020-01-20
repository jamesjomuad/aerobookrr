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
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController'
    ];

    public $formConfig = '$/bookrr/store/controllers/cart/config_form.yaml';
    public $listConfig = '$/bookrr/store/controllers/cart/config_list.yaml';
    public $relationConfig = '$/bookrr/store/controllers/cart/config_relation.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Bookrr.Store', 'store', 'cart');

        $this->model = new CartModel;

        $this->currency = '$';

        $this->addJs('/plugins/bookrr/store/assets/js/cart.js');
    }

    public function update($recordId,$context = null)
    {
        $this->bodyClass = 'compact-container';

        $this->model = $this->model->find($recordId);

        $this->addViewPath(dirname(__DIR__)."\controllers/cart");

        if(request()->header('x-october-request-handler') == "form::onRefresh" AND input('Product.pivot'))
        {
            $pivot = array_map(function($value){ return ['quantity' => (int) $value['quantity']]; }, input('Product.pivot') );

            $this->model->products()->sync($pivot);
        }

        return $this->asExtension('FormController')->update($recordId, $context);
    }

    public function formBeforeSave($model)
    {
        // # Update pivot data
        // $model->products->each(function($product){
        //     $product->pivot->quantity = 1;
        // });

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

    public function onRelationManagePivotCreate() {
        $result = $this->asExtension('RelationController')->onRelationManagePivotCreate();

        $result['#Form-field-Cart-total-group'] = $this->formRenderField('total', ['useContainer'=>false]);

        return $result;
    }

    public function onRelationManagePivotUpdate() {
        $result = $this->asExtension('RelationController')->onRelationManagePivotUpdate();

        $result['#Form-field-Cart-total-group'] = $this->formRenderField('total', ['useContainer'=>false]);

        return $result;
    }

    public function formExtendRefreshData($host)
    {
        // if(input('Product.pivot'))
        // {
        //     $pivot = array_map(function($value){ return ['quantity' => (int) $value['quantity']]; }, input('Product.pivot') );

        //     $this->model->products()->sync($pivot);
        // }
    }

    // public function onRelationManagePivotUpdate() {}
    // public function relationExtendViewWidget() {}
    // public function relationExtendManageWidget() {}
    // public function relationExtendPivotWidget() {}
    // public function relationExtendRefreshResults() {}

}