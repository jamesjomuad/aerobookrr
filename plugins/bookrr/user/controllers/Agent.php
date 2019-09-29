<?php namespace Aeroparks\User\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Agent Back-end Controller
 */
class Agent extends Controller
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

        BackendMenu::setContext('Aeroparks.User', 'user', 'agent');
    }
}
