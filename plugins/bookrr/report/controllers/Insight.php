<?php namespace Bookrr\Report\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Insight Back-end Controller
 */
class Insight extends Controller
{
    
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Aeroparks.Report', 'report', 'insight');
    }

    public function index()
    {
        $this->pageTitle = 'Insights';
        $this->addJs('/plugins/aeroparks/report/assets/js/mdbootstrap-4.7.5.min.js');
        $this->addJs('/plugins/aeroparks/report/assets/js/insights.js');
    }

}
