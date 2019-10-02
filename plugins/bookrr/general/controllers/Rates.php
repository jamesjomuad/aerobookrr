<?php namespace Bookrr\General\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Rates Back-end Controller
 */
class Rates extends Controller
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

        BackendMenu::setContext('Bookrr.General', 'general', 'rates');
    }
}
