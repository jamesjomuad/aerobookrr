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

        Event::listen('backend.page.beforeDisplay', function ($controller, $action, $params) {
            if(request()->is('backend') OR request()->is('backend/*'))
            {
                $controller->addCss('/plugins/bookrr/general/assets/css/style.css');  
            }
        });
    }

    public function pluginDetails()
    {
        return [
            'name'        => 'Bookrr',
            'description' => 'Provides bookrr functionality.',
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
            'bookrr.general.*' => [
                'tab' => 'Bookrr',
                'label' => 'General settings'
            ],
            'bookrr.report.widget' => [
                'tab' => 'Bookrr',
                'label' => 'Dashboard Report Widget'
            ]
        ];
    }

    public function registerReportWidgets()
    {
        return [
            '\Bookrr\General\ReportWidgets\Finder' => [
                'label'   => 'Bookrr - Finder',
                'context' => 'dashboard',
                'permissions' => [
                    'bookrr.widget.booker',
                ],
            ],
            '\Bookrr\General\ReportWidgets\ParkingFeed' => [
                'label'   => 'Bookrr - Aero Feed',
                'context' => 'dashboard',
                'permissions' => [
                    'bookrr.widget.*',
                ],
            ],
            '\Bookrr\General\ReportWidgets\ParkingSlot' => [
                'label'   => 'Bookrr - Parking Slot',
                'context' => 'dashboard',
                'permissions' => [
                    'bookrr.widget.booker',
                ],
            ],
            '\Bookrr\General\ReportWidgets\BookingStat' => [
                'label'   => 'Bookrr - Booking Stat',
                'context' => 'dashboard',
                'permissions' => [
                    'bookrr.widget.bookingstat',
                ],
            ],
            '\Bookrr\General\ReportWidgets\Tasks' => [
                'label'   => 'Bookrr - Tasks',
                'context' => 'dashboard',
                'permissions' => [
                    'bookrr.widget.task',
                ],
            ],
            '\Bookrr\General\ReportWidgets\Instafeed' => [
                'label'   => 'Instagram Feed',
                'context' => 'dashboard',
                'permissions' => [
                    'bookrr.widget.*',
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

    public function registerSettings()
    {
        return [
            'bookrr.setting' => [
                'label'       => 'Settings',
                'description' => 'Manage System Settings.',
                'category'    => 'Bookrr Park',
                'icon'        => 'icon-gear',
                'url'         => Backend::url('bookrr/general/setting'),
                'order'       => 1000,
                'keywords'    => 'aeropark setting',
                'permissions' => ['bookrr.general.*']
            ],
            'bookrr.documentation' => [
                'label'       => 'Documentation',
                'description' => 'Tips and Tricks.',
                'category'    => 'Bookrr Park',
                'icon'        => 'icon-book',
                'url'         => Backend::url('bookrr/general/general/documentation'),
                'order'       => 1000,
                'keywords'    => 'aeropark',
                'permissions' => ['bookrr.general.*']
            ],
            // 'bookrr.license' => [
            //     'label'       => 'License',
            //     'description' => 'Manage Web Application License.',
            //     'category'    => 'Bookrr',
            //     'icon'        => 'icon-star-o',
            //     'url'         => Backend::url('bookrr/general/general/license'),
            //     'order'       => 1000,
            //     'keywords'    => 'aeropark',
            //     'permissions' => ['bookrr.general.*']
            // ]
        ];
    }
}
