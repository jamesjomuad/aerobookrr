<?php namespace Bookrr\User\Traits;

use Illuminate\Support\Arr;
use Carbon\Carbon;

trait mapper{

    public function mapCustomer($post = null)
    {
        $post['dateTime_in']    = $post['transpo_to_airport'] ? $post['dateTime_in_2'] : $post['dateTime_in'];

        $post['dateTime_out']   = $post['transpo_from_airport'] ? $post['dateTime_out_2'] : $post['dateTime_out'];

        $post['dateTime_in']    = (new Carbon($post['dateTime_in']))->format('Y-m-d H:i:s');

        $post['dateTime_out']   = (new Carbon($post['dateTime_out']))->format('Y-m-d H:i:s');

        Arr::pull($post, 'dateTime_in_2');

        Arr::pull($post, 'dateTime_out_2');

        return $post;
    }

    public function mapStaff()
    {
        echo "Trait – anotherMethod() executed";
    }

}