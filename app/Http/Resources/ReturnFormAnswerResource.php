<?php

namespace App\Http\Resources;

use App\Models\ReturnFormAnswer;

class ReturnFormAnswerResource
{
    public function store($data)
    {
        $result = ReturnFormAnswer::insert([
                    'applicationID' => $data['applicationID'],
                    'questionID' => $data['questionID'],
                    'answer_text' => $data['answer_text']
                ]);

        return $result;
    }
}
