<?php namespace Bookrr\Store\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Category Back-end Controller
 */
class Category extends Controller
{
    public $requiredPermissions = [
        'aeroparks.productCategory.read'
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

        BackendMenu::setContext('Bookrr.Store', 'store', 'category');
    }
}
