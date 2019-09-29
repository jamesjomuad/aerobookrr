<?php namespace Bookrr\Report\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Bookrr\Report\Controllers\BaseController;

/**
 * Vehicle Back-end Controller
 */
class Vehicle extends BaseController
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

        BackendMenu::setContext('Aeroparks.Report', 'report', 'vehicle');
    }
}
