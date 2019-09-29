<?php namespace Aeroparks\General\ReportWidgets;

use Backend\Classes\ReportWidgetBase;
use Aeroparks\Bay\Models\Bay;

class ParkingSlot extends ReportWidgetBase
{

    public function render()
    {
        $this->vars['available'] = Bay::countAvailable();

        $this->vars['occupied'] = Bay::countOccupied();

        return $this->makePartial('widget');
    }
    
}
