<?php

namespace App\Http\Resources;

use App\Models\CancelApplication;

class CancelApplicationResource
{
    public function store($data)
    {
        $result = CancelApplication::insert([
                    'applicationID' => $data['applicationID'],
                    'result' => $data['result']
                ]);

        return $result;
    }
}
