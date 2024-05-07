<?php
namespace App\Services;

use App\Http\Resources\AdminResource;
use App\Models\Admin;
use Illuminate\Support\Facades\Cache;
 
class AdminService
{
    public function getAdmins()
    {
        $admins = Admin::get();

        $admins = AdminResource::collection($admins);

        return $admins;
    }
    public function createAdmin($email , $code)
    {
        Admin::create($email);

        Cache::forever('admin_email', $email);
        Cache::put('code', $code , now()->addHour());
    }

    public function updateAdmin(array $user)
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
        
        Admin::currentEmail()->update($data);
    }
}