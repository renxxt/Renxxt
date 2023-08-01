<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\Management;

class ManagementController extends Controller
{
    protected $managementModel;

    public function __construct(Management $managementModel)
    {
        $this->managementModel = $managementModel;
    }

    public function userList(Request $request)
    {
        if ($request->isMethod('post')) {
            $departmentID = $request->input('departmentID');
            if ($departmentID > 0) {
                $result = $this->managementModel->userList($departmentID);
            } else {
                $result = $this->managementModel->allUserList();
            }
        } else {
            $departmentID = 0;
            $result = $this->managementModel->allUserList();
        }

        $departments = $this->managementModel->getDepartments();
        return view('userManagement.userList', ['departments' => $departments, 'departmentID' => $departmentID, 'result' => $result]);
    }

    public function createUser(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'name' => 'required',
                'uid' => 'required|unique:staffs',
                'phonenumber' => 'required|digits:10',
                'email' => 'required|email:rfc,dns|unique:staffs',
                'department' => 'required',
                'position' => 'required'
            ]);

            $data['password'] = Hash::make(substr($data['phonenumber'], 4, 6));
            $result = $this->managementModel->createUser($data);
            return redirect()->route('userManagement.userList');
        } else {
            $positions = $this->managementModel->getPositions();
            $departments = $this->managementModel->getDepartments();

            return view('userManagement.createUser', ['positions' => $positions, 'departments' => $departments]);
        }
    }

    public function editUser(Request $request)
    {
        $staffID = $request->input('staffID');
        $result = $this->managementModel->editUser($staffID);
        $positions = $this->managementModel->getPositions();
        $departments = $this->managementModel->getDepartments();

        return view('userManagement.editUser', ['positions' => $positions, 'departments' => $departments, 'result' => $result]);
    }

    public function updateUser(Request $request)
    {
        $staffID = $request->input('staffID');
        $data = $request->validate([
            'name' => 'required',
            'department' => 'required',
            'email' => ['required',
                Rule::unique('staffs')->ignore($staffID, 'staffID')
            ],
            'phonenumber' => 'required|digits:10',
            'position' => 'required'
        ]);

        $result = $this->managementModel->updateUser($request->all());
        return redirect()->route('userManagement.userList');
    }

    public function deleteUser(Request $request)
    {
        $staffID = $request->input('staffID');
        $result = $this->managementModel->deleteUser($staffID);

        echo($result);
    }
}
