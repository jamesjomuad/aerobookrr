<?php namespace Bookrr\Report;

use Backend;
use System\Classes\PluginBase;
use BackendAuth;


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
            'bookrr.report.bay' => [
                'tab' => 'Reports',
                'label' => 'Bay Reports',
                'order' => 300
            ],
            'bookrr.report.insight' => [
                'tab' => 'Reports',
                'label' => 'Insights reports',
                'order' => 301
            ],
            'bookrr.report.revenue' => [
                'tab' => 'Reports',
                'label' => 'Revenue reports',
                'order' => 302
            ],
            'bookrr.report.manifest' => [
                'tab' => 'Reports',
                'label' => 'Manifest reports',
                'order' => 303
            ],
            'bookrr.report.booking' => [
                'tab' => 'Reports',
                'label' => 'Booking reports',
                'order' => 304
            ],
            'bookrr.report.user' => [
                'tab' => 'Reports',
                'label' => 'User reports',
                'order' => 305
            ],
            'bookrr.report.vehicle' => [
                'tab' => 'Reports',
                'label' => 'Vehicle reports',
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

        foreach($navs['report']['sideMenu'] as $key=>$val){
            if(BackendAuth::getUser()->hasPermission('bookrr.report.'.$key)){
                $navs['report']['url'] = $val['url'];
                break;
            }
        }

        return $navs;
    }

}
