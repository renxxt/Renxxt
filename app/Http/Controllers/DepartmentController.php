<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\DepartmentResource AS Department;

class DepartmentController extends Controller
{
    protected $department;

    public function __construct(Department $department)
    {
        $this->department = $department;
    }

    public function list()
    {
        $result = $this->department->list();
        return response()->json($result);
    }
}
