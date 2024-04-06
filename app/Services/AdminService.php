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

    public function handleRegistrationData(array $user, $request)
    {
        $data =
        [
            'mobile' => $user['mobile'],
            'username' => $user['username'],
            'password' => $user['password'],
            'bio' => $user['bio'],
        ];

        if ($request->hasFile('photo'))
        {
            $data['photo'] = $this->photoPath($user);
        }

        if ($request->filled(['longitude', 'latitude']))
        {
            $data['location'] = $this->locationPath($user);
        }
        hashing($data);

        return $data;
    }
}