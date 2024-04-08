<?php
namespace App\Services;
 
class UserService
{
    public function handleRegistrationData(array $user, $request)
    {
        $data =
        [
            'mobile' => $user['mobile'],
            'username' => $user['username'],
            'password' => $user['password'],
        ];

        if ($request->filled(['longitude', 'latitude']))
        {
            $data['location'] = locationPath($user);
        }

        hashing($data);

        return $data;
    }
}