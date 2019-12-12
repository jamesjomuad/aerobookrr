<?php namespace Bookrr\User\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Vehicle Back-end Controller
 */
class Vehicle extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Bookrr.User', 'user', 'vehicle');
    }

}
