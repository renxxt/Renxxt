<?php

namespace App\Http\Resources;

use App\Models\Question;

class QuestionResource
{
    public function list()
    {
        $result = Question::get();

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

    public function pickupList()
    {
        $result = Question::select('questions.*', 'P.required')
                    ->leftjoin('pickupform AS P', 'P.questionID', '=', 'questions.questionID')
                    ->orderBy('P.order', 'desc')
                    ->get();

        return $result;
    }

    public function returnList()
    {
        $result = Question::select('questions.*', 'R.required')
                    ->leftjoin('returnform AS R', 'R.questionID', '=', 'questions.questionID')
                    ->orderBy('R.order', 'desc')
                    ->get();

        return $result;
    }
}
