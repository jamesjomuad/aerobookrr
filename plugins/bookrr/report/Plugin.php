<?php namespace Bookrr\Report;

use Backend;
use System\Classes\PluginBase;


class Plugin extends PluginBase
{

    public function pluginDetails()
    {
        return [
            'name'        => 'Aero Reports',
            'description' => 'Generate customizable reports.',
            'author'      => 'Jomuad',
            'icon'        => 'icon-leaf'
        ];
    }
    
    public function registerPermissions()
    {
        return [
            'bookrr.report.*' => [
                'tab' => 'Aeropark Reports',
                'label' => 'Manage and process Reports'
            ],
            'bookrr.report.read' => [
                'tab' => 'Aeropark Reports',
                'label' => 'Can View reports',
                'order' => 202,
            ]
        ];
    }

    public function registerNavigation()
    {
        return [
            'report' => [
                'label'       => 'Reports',
                'url'         => Backend::url('bookrr/report/bay'),
                'icon'        => 'icon-area-chart',
                'permissions' => ['bookrr.report.*'],
                'order'       => 915,

                'sideMenu' => [
                    'bay' => [
                        'label'       => 'Bay',
                        'url'         => Backend::url('bookrr/report/bay'),
                        'icon'        => 'fa fa-chart-area',
                        'permissions' => ['bookrr.report.*'],
                    ],
                    'insight' => [
                        'label'       => 'Insights',
                        'url'         => Backend::url('bookrr/report/insight'),
                        'icon'        => 'fa fa-chart-pie',
                        'permissions' => ['bookrr.report.*'],
                    ],
                    'revenue' => [
                        'label'       => 'Revenue',
                        'url'         => Backend::url('bookrr/report/revenue'),
                        'icon'        => 'fa fa-chart-bar',
                        'permissions' => ['bookrr.report.*'],
                    ],
                    'driver' => [
                        'label'       => "Driver's Manifest",
                        'url'         => Backend::url('bookrr/report/driver'),
                        'icon'        => 'icon-bar-chart',
                        'permissions' => ['bookrr.report.*'],
                    ],
                    'booking' => [
                        'label'       => 'Bookings',
                        'url'         => Backend::url('bookrr/report/booking'),
                        'icon'        => 'fa fa-chart-line',
                        'permissions' => ['bookrr.report.*'],
                    ],
                    'customer' => [
                        'label'       => 'Customers',
                        'url'         => Backend::url('bookrr/report/customer'),
                        'icon'        => 'fa fa-chart-bar',
                        'permissions' => ['bookrr.report.*'],
                    ],
                    'vehicle' => [
                        'label'       => 'Vehicles',
                        'url'         => Backend::url('bookrr/report/vehicle'),
                        'icon'        => 'fa fa-chart-area',
                        'permissions' => ['bookrr.report.*'],
                    ],
                    'contact' => [
                        'label'       => 'Contacts',
                        'url'         => Backend::url('bookrr/report/contact'),
                        'icon'        => 'icon-bar-chart',
                        'permissions' => ['bookrr.report.*'],
                    ],
                ]
            ],
        ];
    }
}
