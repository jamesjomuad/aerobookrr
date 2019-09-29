<?php namespace Aeroparks\General\Traits;


trait fflash{

    public function success($message)
    {
        return response()->json(['#layout-flash-messages'=>'<p data-control="flash-message" class="flash-message fade success" data-interval="5">'.$message.'</p>'], 200);
    }

    public function info($message)
    {
        return response()->json(['#layout-flash-messages'=>'<p data-control="flash-message" class="flash-message fade info" data-interval="5">'.$message.'</p>'], 200);
    }

    public function error($message)
    {
        return response()->json(['#layout-flash-messages'=>'<p data-control="flash-message" class="flash-message fade error" data-interval="5">'.$message.'</p>'], 200);
    }

}