<?php namespace Bookrr\Notify\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Notify Back-end Controller
 */
class Notify extends Controller
{

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Bookrr.Notify', 'notify', 'notify');
    }

    public function index()
    {
        $this->pageTitle = "Notifications";
        $this->bodyClass = "notify";

        $this->addJs('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js');
        $this->addJs('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js');
    }

}