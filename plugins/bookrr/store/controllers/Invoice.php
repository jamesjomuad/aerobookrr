<?php namespace Bookrr\Store\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use \Carbon\Carbon;

/**
 * Invoice Back-end Controller
 */
class Invoice extends Controller
{
    public $requiredPermissions = [
        'bookrr.store.invoice'
    ];

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';


    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Bookrr.Store', 'store', 'invoice');
    }

    public function create()
    {
        $this->pageTitle = 'Invoice';

        $this->addCss('/plugins/bookrr/store/assets/style.css',str_random(5));
        $this->addJs('/plugins/bookrr/store/assets/script.js',str_random(5));

        $this->vars['invoice_date'] = (new Carbon())->now()->format('M d, Y');
        $this->vars['invoice_due'] = (new Carbon())->now()->addDays(7)->format('M d, Y');

        $this->vars['invoice'] = $this->makePartial('invoice');
    }
}