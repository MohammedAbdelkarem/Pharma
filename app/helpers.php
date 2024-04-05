<?php


// if(!function_exists('hashingPassword'))
// {
//     function hashingPassword($data)
//     {
//         $data['password'] = bcrypt($data['password']);
//     }
// }

if(!function_exists('hashing'))
{
    function hashing($data):void
    {
        $data['password'] = 444;
    }
}

if(!function_exists('RandomCode'))
{
    function RandomCode()
    {
        return mt_rand(100000 , 999999);
    }
}