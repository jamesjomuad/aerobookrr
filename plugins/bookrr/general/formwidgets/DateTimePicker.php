<?php namespace Bookrr\General\FormWidgets;

use Backend\Classes\FormWidgetBase;
use Backend\Classes\FormField;
use October\Rain\Html\Helper as HtmlHelper;
use \Carbon\Carbon;



class DateTimePicker extends FormWidgetBase
{

    /**
    * @var bool Display mode: datetime, date, time.
    */
    public $mode = 'datetime';

    /**
    * @var string the minimum/earliest date that can be selected.
    * eg: 2000-01-01
    */
    public $minDate = null;

    /**
    * @var string the maximum/latest date that can be selected.
    * eg: 2020-12-31
    */
    public $maxDate = null;

    public $dependsOn;

    protected $defaultAlias = 'bookrr_general_date_time_picker';


    public function init()
    {
        $this->fillFromConfig([
            'mode',
            'minDate',
            'maxDate',
            'dependsOn',
        ]);
    }

    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('datetimepicker');
    }

    public function prepareVars()
    {
        $this->vars['name'] = $this->formField->getName();
        $this->vars['value'] = $this->getLoadValue();
        $this->vars['model'] = $this->model;
    }

    public function loadAssets()
    {
        $this->addCss('css/datetimepicker.css', 'widget-datetimepicker','v1.2');
        $this->addJs('js/datetimepicker.js', 'widget-datetimepicker');
    }

    public function getSaveValue($value)
    {
        return (new Carbon())->parse($value)->format('Y-m-d H:i:s');
    }

    public function getFormatValue()
    {
        if($this->getLoadValue())
        {
            return (new \Carbon\Carbon())
            ->parse($this->getLoadValue())
            ->format('d/m/Y g:i A');
        }
        return null;
    }

    public function getJSOption()
    {
        $mode = $this->mode;
        $option = '{
            format: "DD-MM-YYYY LT"
        }';

        if($mode == 'date')
        {
            if($this->minDate)
            {
                $option = "{minDate: ".$this->minDate.",format: 'DD-MM-YYYY'}";
            }
                
            $option = "{format: 'DD-MM-YYYY'}";
        }
        elseif($mode == 'time')
        {
            $option = "{format: 'LT'}";
        }

        return $option;
    }

    public function getDependsOnID($suffix=null)
    {
        if($this->dependsOn==null){return null;}

        return "#"
        . (new \ReflectionClass($this))->getShortName() 
        . '-' 
        . camel_case('form_'.$this->dependsOn)
        . '-'
        . 'input'
        . '-'
        . $this->dependsOn;
    }
}