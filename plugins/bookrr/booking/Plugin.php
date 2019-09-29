<?php namespace Aeroparks\Booking;

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
            'aeroparks.booking.park' => [
                'tab' => 'Aeroparks Booking',
                'label' => 'Manage Booking parks'
            ],
            'aeroparks.booking.rent' => [
                'tab' => 'Aeroparks Booking',
                'label' => 'Manage Booking rentals'
            ],
            'aeroparks.movement' => [
                'tab' => 'Aeroparks Booking',
                'label' => 'Manage Booking Key Movements'
            ],
        ];
    }

    public function registerNavigation()
    {
        return [
            'booking' => [
                'label'       => 'Booking',
                'url'         => Backend::url('aeroparks/booking/parking'),
                'icon'        => 'icon-calendar',
                'permissions' => ['aeroparks.booking.*'],
                'order'       => 905,
                'sideMenu'    => [
                    'parking' => [
                        'label'       => 'Parking List',
                        'url'         => Backend::url('aeroparks/booking/parking'),
                        'icon'        => 'icon-car',
                        'permissions' => ['aeroparks.booking.park'],
                    ],
                    'parking-calendar' => [
                        'label'       => 'Parking Calendar',
                        'url'         => Backend::url('aeroparks/booking/parkingcalendar'),
                        'icon'        => 'icon-calendar',
                        'permissions' => ['aeroparks.booking.park'],
                    ],
                    // 'rental' => [
                    //     'label'       => 'Rental List',
                    //     'url'         => Backend::url('aeroparks/booking/rental'),
                    //     'icon'        => 'icon-car',
                    //     'permissions' => ['aeroparks.booking.rent'],
                    // ],
                    // 'rental-calendar' => [
                    //     'label'       => 'Rental Calendar',
                    //     'url'         => Backend::url('aeroparks/booking/rental'),
                    //     'icon'        => 'icon-calendar',
                    //     'permissions' => ['aeroparks.booking.rent'],
                    // ],
                ]
            ],
            'move_key' => [
                'label'       => 'Move Key',
                'url'         => Backend::url('aeroparks/booking/movement'),
                'icon'        => 'fa fa-key',
                'permissions' => ['aeroparks.movement'],
                'order'       => 904,
                'roles'       => ['staff']
            ]
        ];
    }
}
