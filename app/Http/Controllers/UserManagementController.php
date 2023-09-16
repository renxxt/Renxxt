<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Lib;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource AS User;
use App\Http\Resources\PositionResource AS Position;
use App\Http\Resources\DepartmentResource AS Department;

class UserManagementController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(Request $request)
    {
        $data = $request->validate([
            'departmentID' => ['integer'],
            'positionID' => ['integer']
        ]);
        if (isset($data['positionID'])) {
            $resource = new Position();
            $result = $resource->getOrder($data['positionID']);
            $data['order'] = $result['order'];
        }
        $result = $this->user->list($data);
        return response()->json($result);
    }

    public function list()
    {
        $this->lib->adminAccess();
        $departmentID = 0;
        $result = $this->user->list($departmentID);
        $resource = new Department();
        $departments = $resource->list();
        return view('userManagement.userList', ['result' => $result, 'departmentID' => $departmentID, 'departments' => $departments]);
    }

    public function create()
    {
        $this->lib->adminAccess();
        $resource = new Position();
        $positions = $resource->list();
        $resource = new Department();
        $departments = $resource->list();
        return view('userManagement.createUser', ['positions' => $positions, 'departments' => $departments]);
    }

    public function store(Request $request)
    {
        $this->lib->adminAccess();
        $data = $request->validate([
            'uid' => [
                'required',
                'unique:users'
            ],
            'name' => ['required'],
            'phonenumber' => [
                'required',
                'digits:10'
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                'unique:users'
            ],
            'department' => ['required'],
            'position' => ['required'],
            'superior' => ['required']
        ]);

        $data['password'] = Hash::make(substr($data['phonenumber'], 4, 6));
        $result = $this->user->store($data);
        return redirect()->route('userManagement.list');
    }

    public function show($id)
    {
        $this->lib->adminAccess();
        $resource = new Department();
        $departments = $resource->list();
        $resource = new Position();
        $positions = $resource->list();
        $result = $this->user->show($id);
        $order = $resource->getOrder($result['positionID']);
        $suppliers = $this->user->list($order);

        return view('userManagement.editUser', ['positions' => $positions, 'departments' => $departments, 'result' => $result, 'suppliers' => $suppliers]);
    }

    public function update(Request $request)
    {
        $this->lib->adminAccess();
        $userID = $request->input('userID');
        $data = $request->validate([
            'userID' => ['required'],
            'name' => ['required'],
            'phonenumber' => [
                'required',
                'digits:10'
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                Rule::unique('users')->ignore($userID, 'userID')
            ],
            'department' => ['required'],
            'position' => ['required'],
            'superior' => ['required']
        ]);

        $data['password'] = Hash::make(substr($data['phonenumber'], 4, 6));
        $result = $this->user->update($data);
        return redirect()->route('userManagement.list');
    }

    public function delete(Request $request)
    {
        $this->lib->adminAccess();
        $data = $request->validate([
            'userID' => ['required']
        ]);
        $result = $this->user->delete($data['userID']);

        echo($result);
    }
}