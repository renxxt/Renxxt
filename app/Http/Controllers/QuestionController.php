<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Resources\QuestionResource AS Question;

class QuestionController extends Controller
{
    protected $question;

    public function __construct(Question $question)
    {
        $this->question = $question;
    }

    public function getQuestion()
    {
        $result = $this->question->list();
        return response()->json($result);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'question' => [
                'required',
                'string',
                Rule::unique('questions')
            ]
        ]);
        $data['type'] = 1;
        $result = $this->question->store($data);

        return $result;
    }
}
