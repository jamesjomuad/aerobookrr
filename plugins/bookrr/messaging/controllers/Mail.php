<?php namespace Bookrr\Messaging\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Mail Back-end Controller
 */
class Mail extends Controller
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

        BackendMenu::setContext('Aeroparks.Messaging', 'messaging', 'mail');
    }

    public function index()
    {
        $this->pageTitle = 'Mail';
    }

    public function create()
    {
        $this->pageTitle = 'Compose Email';
        // return $this->asExtension('FormController')->create();
    }

}