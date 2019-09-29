<?php namespace Bookrr\General\ReportWidgets;

use Backend\Classes\ReportWidgetBase;

class InstaFeed extends ReportWidgetBase
{
    protected $defaultAlias = 'aeroparks_general_instafeed';

    public function render()
    {
        $this->vars['var'] = 'value';
        return $this->makePartial('widget');
    }
    
    public function defineProperties()
    {
        return [
            'title' => [
                'title'             => 'Widget title',
                'default'           => 'Instagram Feed',
                'type'              => 'string',
                'validationPattern' => '^.+$',
                'validationMessage' => 'The Widget Title is required.'
            ],
            'userId' => [
                'title'             => 'User ID',
                'default'           => '10665736023',
                'type'              => 'string'
            ],
            'accessToken' => [
                'title'             => 'Token',
                'default'           => '10665736023.598e3fc.ff4e6c12d9bb466e9c6978a796e8cc4f',
                'type'              => 'string'
            ],
            'resolution' => [
                'title'             => 'Resolution',
                'default'           => 'thumbnail',
                'type'              => 'string'
            ]
        ];
    }
}
