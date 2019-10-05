<?php namespace Bookrr\General\ReportWidgets;

use Backend\Classes\ReportWidgetBase;

class Tasks extends ReportWidgetBase
{
    protected $defaultAlias = 'bookrr_general_tasks';

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
                'default'           => 'Unperformed Tasks',
                'type'              => 'string',
                'validationPattern' => '^.+$',
                'validationMessage' => 'The Widget Title is required.'
            ],
            'days' => [
                'title'             => 'Number of rows to display',
                'default'           => '7',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$'
            ]
        ];
    }
}
