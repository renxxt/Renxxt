<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Lib;
use Illuminate\Validation\Rule;
use App\Http\Resources\QuestionResource AS Question;

class QuestionController extends Controller
{
    protected $lib;
    protected $question;

    public function __construct(Lib $lib, Question $question)
    {
        $this->lib = $lib;
        $this->question = $question;
    }

    public function getQuestion()
    {
        $result = $this->question->list();

        return response()->json($result);
    }

    public function store(Request $request)
    {
        $access = $this->lib->adminAccess();
        if ($access instanceof \Illuminate\Http\RedirectResponse) {
            return $access;
        }

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
