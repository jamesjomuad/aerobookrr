<?php namespace Bookrr\Booking;

use Backend;
use System\Classes\PluginBase;
use Backend\Models\User as UserModel;
use Bookrr\Stripe\Models\Transaction;
use BackendAuth;


class Plugin extends PluginBase
{
    public $elevated = true;

    public function pluginDetails()
    {
        return [
            'name'        => 'Bookrr Park',
            'description' => 'Control page for parkings',
            'author'      => 'Jomuad',
            'icon'        => 'icon-car'
        ];
    }

    public function boot()
    {
        # Extend User
        UserModel::extend(function($model){
            # Extend Relations
            $model->hasMany['parking'] = [
                'Bookrr\Booking\Models\Parking',
                'delete' => true
            ];

            return $model;
        });

        # Extend Transaction from Stripe
        Transaction::extend(function($model){

            # Event Listener
            $model->bindEvent('model.afterCreate', function() use($model) {
                if($book = \Bookrr\Booking\Models\Parking::find(input('id')))
                {
                    $book->cart->setPaid($model->response);
                    $model->user_id = BackendAuth::getUser()->id;
                    $model->customer_id = $book->customer->id;
                    $model->other_id = $book->cart->id;
                    $model->save();
                }
            });

            return $model;
        });
    }

    public function registerComponents()
    {
        return [
            'Bookrr\Booking\Components\Quoter' => 'Quoter'
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
                    ],
                    'move_key' => [
                        'label'       => 'Move Key',
                        'url'         => Backend::url('bookrr/booking/movement'),
                        'icon'        => 'icon-key',
                        'permissions' => ['bookrr.booking.movement'],
                        'roles'       => ['staff']
                    ]
                ]
            ]
        ];
    }

}
