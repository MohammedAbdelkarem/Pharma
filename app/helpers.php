<?php

use App\Models\Admin;
use Illuminate\Support\Facades\Cache;


// if(!function_exists('hashingPassword'))
// {
//     function hashingPassword($data)
//     {
//         $data['password'] = bcrypt($data['password']);
//     }
// }

if(!function_exists('hashing'))
{
    function hashing(&$data):void
    {
        $data['password'] = bcrypt($data['password']);
    }
}

if(!function_exists('RandomCode'))
{
    function RandomCode()
    {
        return mt_rand(100000 , 999999);
    }
}

if(!function_exists('adminToken'))
{
    function adminToken($data)
    {
        $var = $data->createToken('MyApp' , ['admin'])->accessToken;
        return $var;
    }
}

if(!function_exists('userToken'))
{
    function userToken($data)
    {
        $var = $data->createToken('MyApp' , ['user'])->accessToken;
        return $var;
    }
}

if(!function_exists('photoPath'))
{
    function photoPath($image)
    {
        $path = time().'.'.$image->getClientOriginalExtension();
        $image->move(public_path('image') , $path);
        $path = 'image/'.$path;

        return $path;
    }
}

if(!function_exists('locationPath'))
{
    function locationPath($data)
    {
        $path = 'https://www.google.com/maps?q='.$data['latitude'].','.$data['longitude'];
        return $path;
    }
}

if(!function_exists('admin_id'))
{
    function admin_id()
    {
        return Admin::currentEmail()->pluck('id')->first();
    }
}