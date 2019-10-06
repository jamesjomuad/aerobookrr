<?php namespace Bookrr\Report;

use Backend;
use System\Classes\PluginBase;
use BackendAuth;


class Plugin extends PluginBase
{
    use \Bookrr\General\Traits\Tool;

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
            'bookrr.report.bay' => [
                'tab' => 'Reports',
                'label' => 'Manage Bay',
                'order' => 300
            ],
            'bookrr.report.insight' => [
                'tab' => 'Reports',
                'label' => 'Manage Insights',
                'order' => 301
            ],
            'bookrr.report.revenue' => [
                'tab' => 'Reports',
                'label' => 'Manage Revenue',
                'order' => 302
            ],
            'bookrr.report.manifest' => [
                'tab' => 'Reports',
                'label' => 'Manage Manifest',
                'order' => 303
            ],
            'bookrr.report.booking' => [
                'tab' => 'Reports',
                'label' => 'Manage Booking',
                'order' => 304
            ],
            'bookrr.report.user' => [
                'tab' => 'Reports',
                'label' => 'Manage User',
                'order' => 305
            ],
            'bookrr.report.vehicle' => [
                'tab' => 'Reports',
                'label' => 'Manage Vehicle',
                'order' => 306
            ]
        ];
    }

    public function registerNavigation()
    {
        $navs = [
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
                        'icon'        => 'icon-area-chart',
                        'permissions' => ['bookrr.report.bay'],
                    ],
                    'insight' => [
                        'label'       => 'Insights',
                        'url'         => Backend::url('bookrr/report/insight'),
                        'icon'        => 'icon-line-chart',
                        'permissions' => ['bookrr.report.insight'],
                    ],
                    'revenue' => [
                        'label'       => 'Revenue',
                        'url'         => Backend::url('bookrr/report/revenue'),
                        'icon'        => 'icon-area-chart',
                        'permissions' => ['bookrr.report.revenue'],
                    ],
                    'driver' => [
                        'label'       => "Driver's Manifest",
                        'url'         => Backend::url('bookrr/report/driver'),
                        'icon'        => 'icon-bar-chart',
                        'permissions' => ['bookrr.report.manifest'],
                    ],
                    'booking' => [
                        'label'       => 'Bookings',
                        'url'         => Backend::url('bookrr/report/booking'),
                        'icon'        => 'fa fa-chart-line',
                        'permissions' => ['bookrr.report.booking'],
                    ],
                    'user' => [
                        'label'       => 'Users',
                        'url'         => Backend::url('bookrr/report/user'),
                        'icon'        => 'icon-users',
                        'permissions' => ['bookrr.report.user'],
                    ],
                    'vehicle' => [
                        'label'       => 'Vehicles',
                        'url'         => Backend::url('bookrr/report/vehicle'),
                        'icon'        => 'fa fa-chart-area',
                        'permissions' => ['bookrr.report.vehicle'],
                    ]
                ]
            ],
        ];

        return $this->setDefaultNav($navs,'bookrr.report');
    }

}
