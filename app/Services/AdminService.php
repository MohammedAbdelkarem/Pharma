<?php
namespace App\Services;
 
class AdminService
{
    public function photoPath($request)
    {
        $image = $request['photo'];
        $path = time().'.'.$image->getClientOriginalExtension();
        $image->move(public_path('image') , $path);
        $path = 'image/'.$path;

        return $path;
    }

    public function locationPath($request)
    {
        $path = 'https://www.google.com/maps?q='.$request['latitude'].','.$request['longitude'];
        return $path;
    }
}