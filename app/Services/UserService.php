<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
 
class UserService
{
    public function createUser($email , $code)
    {
        User::create($email);

        Cache::forever('user_email', $email);
        Cache::put('code', $code , now()->addHour());
    }

    public function updateUser(array $user)
    {
        
        $fieldsToUpdate = ['username', 'mobile', 'password'];

        foreach ($fieldsToUpdate as $field)
        {
            if (isset($user[$field]))
            {
                $data[$field] = $user[$field];
            }
        }
        if (isset($user['longitude']) && isset($user['latitude']))
        {
            $data['location'] = locationPath($user);
        }
        if(isset($user['password']))
        {
            hashing($data);
        }
        
        User::currentEmail()->update($data);
    }
}