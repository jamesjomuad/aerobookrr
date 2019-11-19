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
                'tab' => 'Booking',
                'label' => 'Manage Booking'
            ],
            'bookrr.movement' => [
                'tab' => 'Booking',
                'label' => 'Manage Key Movements'
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
                        'label'       => 'List',
                        'url'         => Backend::url('bookrr/booking/parking'),
                        'icon'        => 'icon-car',
                        'permissions' => ['bookrr.booking.park'],
                    ],
                    'parking-calendar' => [
                        'label'       => 'Calendar',
                        'url'         => Backend::url('bookrr/booking/calendar'),
                        'icon'        => 'icon-calendar',
                        'permissions' => ['bookrr.booking.park'],
                    ]
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
