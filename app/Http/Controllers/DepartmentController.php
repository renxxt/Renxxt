<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Lib;
use Illuminate\Validation\Rule;
use App\Http\Resources\DepartmentResource AS Department;

class DepartmentController extends Controller
{
    protected $lib;
    protected $department;

    public function __construct(Lib $lib, Department $department)
    {
        $this->lib = $lib;
        $this->department = $department;
    }

    public function list()
    {
        $access = $this->lib->adminAccess();
        if ($access instanceof \Illuminate\Http\RedirectResponse) {
            return $access;
        }

        $result = $this->department->list();
        return view('departmentManagement.departmentlist', ['result' => $result]);
    }

    public function store(Request $request)
    {
        $access = $this->lib->adminAccess();
        if ($access instanceof \Illuminate\Http\RedirectResponse) {
            return $access;
        }

        $data = $request->validate([
            'department' => [
                'required',
                'string',
                Rule::unique('departments')
            ]
        ]);

        $result = $this->department->store($data);
        return $result;
    }

    public function update(Request $request)
    {
        $access = $this->lib->adminAccess();
        if ($access instanceof \Illuminate\Http\RedirectResponse) {
            return $access;
        }

        $departmentID = $request->input('departmentID');
        $data = $request->validate([
            'departmentID' => [
                'required',
                'integer'
            ],
            'department' => [
                'required',
                'string',
                Rule::unique('departments')->ignore($departmentID, 'departmentID')
            ]
        ]);

        $result = $this->department->update($data);
        return $result;
    }
}
