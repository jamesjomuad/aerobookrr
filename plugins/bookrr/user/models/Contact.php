<?php namespace Bookrr\User\Models;

use Model;

/**
 * Contact Model
 */
class Contact extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    public $rules = [
        'customer' => 'required'
    ];
    
    public $table = 'aeroparks_user_contacts';

    protected $guarded = ['*'];

    protected $hidden = ['updated_at','deleted_at'];

    protected $fillable = ['primary','title','first_name','last_name','phone','email','address','city','country','state','zip'];

    public $belongsTo = [
        'customer' => ['Bookrr\User\Models\Customer','key' => 'user_id'],
    ];


}
