<?php namespace Bookrr\Report\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Bookrr\Report\Controllers\BaseController;
use \Carbon\Carbon;
use Renatio\DynamicPDF\Classes\PDF;
// use Barryvdh\DomPDF\PDF;

/**
 * Booking Report Back-end Controller
 */
class Booking extends BaseController
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';


    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Aeroparks.Report', 'report', 'booking');
    }

}
