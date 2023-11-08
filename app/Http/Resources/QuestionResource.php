<?php

namespace App\Http\Resources;

use App\Models\Question;

class QuestionResource
{
    public function list()
    {
        $result = Question::where('state', 0)
                    ->get();

        if ($result->count() > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function store($data)
    {
        $result = Question::insert([
                    'question' => $data['question'],
                    'type' => $data['type']
                ]);

        return $result;
    }

    public function update($data)
    {
        $result = Question::where('questionID', $data['questionID'])
                    ->update([
                        'question' => $data['question']
                    ]);

        return $result;
    }

    public function delete($data)
    {
        $result = Question::where('questionID', $data['questionID'])
                    ->update([
                        'state' => 1
                    ]);

        return $result;
    }
}
