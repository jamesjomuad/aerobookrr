<?php namespace Aeroparks\Report\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Revenue Back-end Controller
 */
class Revenue extends Controller
{

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Aeroparks.Report', 'report', 'revenue');
    }

    public function index()
    {
        $this->pageTitle = "Revenue";
        $this->addJs('/plugins/aeroparks/report/assets/js/mdbootstrap-4.7.5.min.js');
        $this->addJs('/plugins/aeroparks/report/assets/js/revenue.js',str_random('5'));
    }

}
