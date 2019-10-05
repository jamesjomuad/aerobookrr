<?php namespace Bookrr\Booking;

use Backend;
use System\Classes\PluginBase;


class Plugin extends PluginBase
{
 
    public function pluginDetails()
    {
        return [
            'name'        => 'Aero Booker',
            'description' => 'Control page for parkings',
            'author'      => 'Jomuad',
            'icon'        => 'icon-car'
        ];
    }

    public function registerPermissions()
    {
        return [
            'bookrr.booking.park' => [
                'tab' => 'Bookrr Booking',
                'label' => 'Manage Booking parks'
            ],
            'bookrr.movement' => [
                'tab' => 'Bookrr Booking',
                'label' => 'Manage Booking Key Movements'
            ],
        ];
    }

    public function registerNavigation()
    {
        return [
            'booking' => [
                'label'       => 'Booking',
                'url'         => Backend::url('bookrr/booking/parking'),
                'icon'        => 'icon-calendar',
                'permissions' => ['bookrr.booking.*'],
                'order'       => 905,
                'sideMenu'    => [
                    'parking' => [
                        'label'       => 'Parking List',
                        'url'         => Backend::url('bookrr/booking/parking'),
                        'icon'        => 'icon-car',
                        'permissions' => ['bookrr.booking.park'],
                    ],
                    'parking-calendar' => [
                        'label'       => 'Parking Calendar',
                        'url'         => Backend::url('bookrr/booking/parkingcalendar'),
                        'icon'        => 'icon-calendar',
                        'permissions' => ['bookrr.booking.park'],
                    ],
                    // 'rental' => [
                    //     'label'       => 'Rental List',
                    //     'url'         => Backend::url('bookrr/booking/rental'),
                    //     'icon'        => 'icon-car',
                    //     'permissions' => ['bookrr.booking.rent'],
                    // ],
                    // 'rental-calendar' => [
                    //     'label'       => 'Rental Calendar',
                    //     'url'         => Backend::url('bookrr/booking/rental'),
                    //     'icon'        => 'icon-calendar',
                    //     'permissions' => ['bookrr.booking.rent'],
                    // ],
                ]
            ],
            'move_key' => [
                'label'       => 'Move Key',
                'url'         => Backend::url('bookrr/booking/movement'),
                'icon'        => 'icon-key',
                'permissions' => ['bookrr.movement'],
                'order'       => 904,
                'roles'       => ['staff']
            ]
        ];
    }
}
