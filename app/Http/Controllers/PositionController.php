<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\PositionResource AS Position;

class PositionController extends Controller
{
    protected $position;

    public function __construct(Position $position)
    {
        $this->position = $position;
    }

    public function list(Request $request)
    {
        $result = $this->position->list();
        return response()->json($result);
    }
}
