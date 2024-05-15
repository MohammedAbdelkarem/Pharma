<?php

use App\Models\{User , Admin};
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

if(!function_exists('getAdminToken'))
{
    function getAdminToken()
    {
        $admin = Admin::currentEmail()->first('id');
        return adminToken($admin);
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

if(!function_exists('getUserToken'))
{
    function getUserToken()
    {
        $user = User::currentEmail()->first('id');
        return userToken($user);
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
if(!function_exists('user_id'))
{
    function user_id()
    {
        return User::currentEmail()->pluck('id')->first();
    }
}
if(!function_exists('configAdminAuth'))
{
    function configAdminAuth($email)
    {
        Cache::forever('admin_email' , $email);

        config(['auth.guards.admin_api.provider' => 'admin']);
    }
}
if(!function_exists('configUserAuth'))
{
    function configUserAuth($email)
    {
        Cache::forever('user_email' , $email);

        config(['auth.guards.user_api.provider' => 'user']);
    }
}