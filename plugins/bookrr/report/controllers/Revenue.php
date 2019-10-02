<?php namespace Bookrr\Report\Controllers;

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

        BackendMenu::setContext('Bookrr.Report', 'report', 'revenue');
    }

    public function index()
    {
        $this->pageTitle = "Revenue";
        $this->addJs('/plugins/bookrr/report/assets/js/mdbootstrap-4.7.5.min.js');
        $this->addJs('/plugins/bookrr/report/assets/js/revenue.js',str_random('5'));
    }

}
