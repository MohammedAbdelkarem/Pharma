<?php

namespace App\Traits;

trait ResponseTrait
{
    public function SendResponse($status = null , $message = null , $data = null)
    {
        $array = [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];
        return response($array);
    }
}

