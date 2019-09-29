<?php namespace Bookrr\General;

use Backend;
use Event;
use System\Classes\PluginBase;
use Backend\Models\User as UserModel;


class Plugin extends PluginBase
{
    public function boot()
    {
        \Event::listen('backend.menu.extendItems', function($manager) {
            // dd($manager);
            // $manager->removeMainMenuItem('renatio.dynamicpdf', 'dynamicpdf');
        });
    }

    public function pluginDetails()
    {
        return [
            'name'        => 'Aeroparks',
            'description' => 'Provides aeroparks functionality.',
            'author'      => 'Jomuad',
            'icon'        => 'icon-car'
        ];
    }

    public function registerComponents()
    {
        return [
            'Bookrr\User\Components\Register' => 'Register',
            'Bookrr\User\Components\Login' => 'Login',
            'Bookrr\Store\Components\Product' => 'Product'
        ];
    }

    public function registerPermissions()
    {
        return [
            'aeroparks.general.*' => [
                'tab' => 'Aeroparks',
                'label' => 'General settings'
            ],
            'aeroparks.report.widget' => [
                'tab' => 'Aeroparks',
                'label' => 'Dashboard Report Widget'
            ]
        ];
    }

    public function registerReportWidgets()
    {
        return [
            '\Bookrr\General\ReportWidgets\Finder' => [
                'label'   => 'Aeroparks - Finder',
                'context' => 'dashboard',
                'permissions' => [
                    'aeroparks.widget.booker',
                ],
            ],
            '\Bookrr\General\ReportWidgets\ParkingFeed' => [
                'label'   => 'Aeroparks - Aero Feed',
                'context' => 'dashboard',
                'permissions' => [
                    'aeroparks.widget.*',
                ],
            ],
            '\Bookrr\General\ReportWidgets\ParkingSlot' => [
                'label'   => 'Aeroparks - Parking Slot',
                'context' => 'dashboard',
                'permissions' => [
                    'aeroparks.widget.booker',
                ],
            ],
            '\Bookrr\General\ReportWidgets\BookingStat' => [
                'label'   => 'Aeroparks - Booking Stat',
                'context' => 'dashboard',
                'permissions' => [
                    'aeroparks.widget.bookingstat',
                ],
            ],
            '\Bookrr\General\ReportWidgets\Tasks' => [
                'label'   => 'Aeroparks - Tasks',
                'context' => 'dashboard',
                'permissions' => [
                    'aeroparks.widget.task',
                ],
            ],
            '\Bookrr\General\ReportWidgets\Instafeed' => [
                'label'   => 'Instagram Feed',
                'context' => 'dashboard',
                'permissions' => [
                    'aeroparks.widget.*',
                ],
            ]
        ];
    }

    public function registerFormWidgets()
    {
        return[
            'Bookrr\General\FormWidgets\DateTimePicker' => [
                'label' => 'Bootstrap Datetime picker',
                'code' => 'datetimepicker'
            ],
            'Bookrr\General\FormWidgets\FinderPlus' => [
                'label' => 'Finder and Selector list',
                'code' => 'finderplus'
            ],
            'Bookrr\General\FormWidgets\Basket' => [
                'label' => 'Basket for items.',
                'code' => 'basket'
            ]
        ];
    }

    public function registerNavigation()
    {
        return []; // Remove this line to activate

        return [
            'general' => [
                'label'       => 'general',
                'url'         => Backend::url('aeroparks/general/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['aeroparks.general.*'],
                'order'       => 500,
            ],
        ];
    }

    public function registerSettings()
    {
        return [
            'aeroparks.setting' => [
                'label'       => 'Settings',
                'description' => 'Manage System Settings.',
                'category'    => 'Aeroparks',
                'icon'        => 'icon-gear',
                'url'         => Backend::url('aeroparks/general/setting'),
                'order'       => 1000,
                'keywords'    => 'aeropark setting',
                'permissions' => ['aeroparks.general.*']
            ],
            'aeroparks.documentation' => [
                'label'       => 'Documentation',
                'description' => 'Tips and Tricks.',
                'category'    => 'Aeroparks',
                'icon'        => 'icon-book',
                'url'         => Backend::url('aeroparks/general/general/documentation'),
                'order'       => 1000,
                'keywords'    => 'aeropark',
                'permissions' => ['aeroparks.general.*']
            ],
            // 'aeroparks.license' => [
            //     'label'       => 'License',
            //     'description' => 'Manage Web Application License.',
            //     'category'    => 'Aeroparks',
            //     'icon'        => 'icon-star-o',
            //     'url'         => Backend::url('aeroparks/general/general/license'),
            //     'order'       => 1000,
            //     'keywords'    => 'aeropark',
            //     'permissions' => ['aeroparks.general.*']
            // ]
        ];
    }
}
