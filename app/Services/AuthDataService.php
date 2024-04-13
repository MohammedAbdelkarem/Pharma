<?php
namespace App\Services;
 
class AuthDataService
{
    public function handleData(array $user)
    {
        
        $fieldsToUpdate = ['username', 'mobile', 'password', 'bio'];

        foreach ($fieldsToUpdate as $field)
        {
            if (isset($user[$field]))
            {
                $data[$field] = $user[$field];
            }
        }
        if (isset($user['photo']))
        {
            $data['photo'] = photoPath($user['photo']);
        }
        
        if (isset($user['longitude']) && isset($user['latitude']))
        {
            $data['location'] = locationPath($user);
        }
        if(isset($user['password']))
        {
            hashing($data);
        }

        return $data;
    }
}