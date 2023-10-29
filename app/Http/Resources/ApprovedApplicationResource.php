<?php

namespace App\Http\Resources;

use App\Models\ApprovedApplication;

class ApprovedApplicationResource
{
    public function store($data)
    {
        $result = ApprovedApplication::insert([
                    'applicationID' => $data['applicationID'],
                    'approved_userID' => $data['userID']
                ]);

        return $result;
    }
}
