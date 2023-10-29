<?php

namespace App\Http\Resources;

use App\Models\PickupFormAnswer;

class PickupFormAnswerResource
{
    public function store($data)
    {
        $result = PickupFormAnswer::insert([
                    'applicationID' => $data['applicationID'],
                    'questionID' => $data['questionID'],
                    'answer_text' => $data['answer_text']
                ]);

        return $result;
    }
}
