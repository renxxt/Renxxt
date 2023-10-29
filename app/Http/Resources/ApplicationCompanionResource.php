<?php

namespace App\Http\Resources;

use App\Models\ApplicationCompanion;

class ApplicationCompanionResource
{
    public function store($data)
    {
        $result = ApplicationCompanion::insert([
            'applicationID' => $data['id'],
            'userID' => $data['userID']
        ]);

        return $result;
    }

    public function deleteAll($id)
    {
        $result = ApplicationCompanion::where('applicationID', $id)->delete();

        return $result;
    }
}
