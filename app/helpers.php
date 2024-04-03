<?php


// if(!function_exists('hashingPassword'))
// {
//     function hashingPassword($data)
//     {
//         $data['password'] = bcrypt($data['password']);
//     }
// }

if(!function_exists('hashingPassword'))
{
    function hashingPassword($data)
    {
        $data['password'] = bcrypt($data['password']);
    }
}