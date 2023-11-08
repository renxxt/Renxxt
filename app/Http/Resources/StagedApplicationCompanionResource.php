<?php

namespace App\Http\Resources;

use App\Models\StagedApplicationCompanion;

class StagedApplicationCompanionResource
{
    public function store($id, $userID)
    {
        $result = StagedApplicationCompanion::insert([
                    'applicationID' => $id,
                    'userID' => $userID
                ]);

        return $result;
    }

    public function deleteAll($id)
    {
        $result = StagedApplicationCompanion::where('applicationID', $id)->delete();

        return $result;
    }

    public function delete($data)
    {
        $result = StagedApplicationCompanion::where('applicationID', $data['applicationID'])
                    ->where('userID', $data['userID'])
                    ->delete();

        return $result;
    }
}
