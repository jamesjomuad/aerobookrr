<?php namespace Bookrr\Report\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Bookrr\Report\Controllers\BaseController;
use Faker\Factory as Faker;

/**
 * Driver Back-end Controller
 */
class Driver extends BaseController
{

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Bookrr.Report', 'report', 'driver');
    }


    public function index()
    {
        $faker = Faker::create();

        $data = [];

        foreach (range(1,25) as $index){
            $data[] = [
                'fname'      => $faker->firstName,
                'lname'      => $faker->lastName,
                'radio'      => $faker->unixTime(),
                'passengers' => $faker->numberBetween(0,9),
            ];
        }

        $this->vars['data'] = $data;
    }
}
