<?php namespace Bookrr\Report\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Bookrr\Report\Controllers\BaseController;
use Carbon\Carbon;
use Faker\Factory as Faker;
use DB;




class Bay extends BaseController
{
    public $requiredPermissions = ['bookrr.report.bay'];

    public $statistics;

    public $expired;

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Bookrr.Report', 'report', 'bay');
    }

    public function index()
    {
        $this->pageTitle = 'Bay Reports';

        $this->vars['faker'] = Faker::create();
    }

    public function statistics()
    {
        $booking = $this->models->booking;

        $daysInWeek = collect([
            Carbon::today()->subDays(1),
            Carbon::today()->subDays(2),
            Carbon::today()->subDays(3),
            Carbon::today()->subDays(4),
            Carbon::today()->subDays(5),
            Carbon::today()->subDays(6),
            Carbon::today()->subDays(7)
        ]);

        return $daysInWeek->mapWithKeys(function($day) use($booking) {
            return [
                $day->format('Y-m-d') => [
                    'carsIn'    => $booking->whereDate('park_in', $day)->count(),
                    'carsOut'   => $booking->whereDate('park_out', $day)->count(),
                    'movements' => $booking->whereDate('park_in', $day)->withCount('movements')->get()->sum('movements_count'),
                    'occupancy' => 0,
                    'free_bays' => 0
                ]
            ];
        });
    }
}