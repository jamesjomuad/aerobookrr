<?php namespace Bookrr\Rates\Models;

use Model;
use \Carbon\Carbon;
use Bookrr\Rates\Models\Settings;

class Rate extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    public $table = 'aeroparks_rates';

    protected $guarded = ['*'];

    public $rules = [
        'name'       => 'required',
        'rate'       => "required|regex:/^\d+(\.\d{1,2})?$/",
        'date_start' => 'required|date',
        'date_end'   => 'required|date|after:date_start'
    ];

    public function getDateStartAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d-m-Y');
    }

    public function getDateEndAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d-m-Y');
    }

    public function filterFields($fields, $context = null)
    {
        // $fields->code->value = "filterFields";

        if($context=='create' AND session()->get('start'))
        {
            $start = session()->get('start');
            $end = session()->get('end');
            $fields->date_start->value = Carbon::createFromTimestamp($start)->format('d-m-Y');
            $fields->date_end->value = Carbon::createFromTimestamp($end)->format('d-m-Y');
        }
    }

    public function beforeSave()
    {
        # validate date range overlap
        $from   = Carbon::parse($this->date_start);
        $to     = Carbon::parse($this->date_end);
        $query  = $this->whereBetween('date_start', [$from,$to]);

        if($query->get()->count())
        {
            $name = $query->first()->name;
            throw new \ValidationException(['date_start' => 'Date Range overlapped with '.$name.'!']);
            return false;
        }
    }

    /*
    *   Scopes
    */
    public function scopeThisMonth($query,$timestamp)
    {
        $query->select('id','name','date_start','date_end','rate','active');

        $query->whereMonth('date_start',$timestamp->month)
            ->whereYear('date_start',$timestamp->year)
        ;

        $query->orWhere(function($nest) use($timestamp) {
            $nest->whereMonth('date_end',$timestamp->month)
                ->whereYear('date_start',$timestamp->year)
            ;
        });
        return $query;
    }

    public function scopeActive($query)
    {
        $query->where('active','1');
        return $query;
    }

    public function scopeInactive($query)
    {
        $query->where('active','');
        return $query;
    }

    public function scopeRate($query)
    {
        $date = Carbon::today();
        
        $query->where('date_end', '>=', $date);

        $query->where('date_start', '<=', $date);

        return $query->first();
    }

    public function scopeAmount($query)
    {
        if($query->rate()->count()>0 AND $query->rate()->active)
        {
            return $query->rate()->rate;
        }

        // Return default rate
        return Settings::get('rate') ? : false ;
    }

    public function scopeIsCurrent($query)
    {
        $today  = Carbon::today();
        $start  = Carbon::parse($this->date_start);
        $end    = Carbon::parse($this->date_end);

        return $start->lessThanOrEqualTo($today) AND $end->greaterThanOrEqualTo($today);
    }

}
