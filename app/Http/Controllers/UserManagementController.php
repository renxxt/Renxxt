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
    protected $lib;
    protected $user;

    public function __construct(Lib $lib, User $user)
    {
        $this->lib = $lib;
        $this->user = $user;
    }

    public function getUser(Request $request)
    {
        $data = $request->validate([
            'departmentID' => [ 'integer' ],
            'positionID' => [ 'integer' ]
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
        $access = $this->lib->adminAccess();
        if ($access instanceof \Illuminate\Http\RedirectResponse) {
            return $access;
        }

        $departmentID = 0;
        $result = $this->user->list($departmentID);
        $resource = new Department();
        $departments = $resource->list();
        return view('userManagement.userList', ['result' => $result, 'departmentID' => $departmentID, 'departments' => $departments]);
    }

    public function create()
    {
        $access = $this->lib->adminAccess();
        if ($access instanceof \Illuminate\Http\RedirectResponse) {
            return $access;
        }

        $resource = new Position();
        $positions = $resource->list();
        $resource = new Department();
        $departments = $resource->list();
        return view('userManagement.createUser', ['positions' => $positions, 'departments' => $departments]);
    }

    public function store(Request $request)
    {
        $access = $this->lib->adminAccess();
        if ($access instanceof \Illuminate\Http\RedirectResponse) {
            return $access;
        }

        $data = $request->validate([
            'uid' => [
                'required',
                'unique:users'
            ],
            'name' => [ 'required', 'string' ],
            'phonenumber' => [ 'required', 'digits:10' ],
            'email' => [
                'required',
                'email:rfc,dns',
                'unique:users'
            ],
            'department' => [ 'required', 'integer' ],
            'position' => [ 'required', 'integer' ],
            'superior' => [ 'required', 'integer' ]
        ]);

        $data['password'] = Hash::make(substr($data['phonenumber'], 4, 6));
        $result = $this->user->store($data);
        return redirect()->route('userManagement.list');
    }

    public function show($id)
    {
        $access = $this->lib->adminAccess();
        if ($access instanceof \Illuminate\Http\RedirectResponse) {
            return $access;
        }

        $resource = new Department();
        $departments = $resource->list();
        $resource = new Position();
        $positions = $resource->list();
        $result = $this->user->show($id);
        if (!$result) {
            $messageData = [
                'type' => "danger",
                'message' => "無該使用者"
            ];
            return redirect()->route('userManagement.list')->with('messageData', [$messageData]);
        }
        $order = $resource->getOrder($result['positionID']);
        $suppliers = $this->user->list($order);

        return view('userManagement.editUser', ['positions' => $positions, 'departments' => $departments, 'result' => $result, 'suppliers' => $suppliers]);
    }

    public function update(Request $request)
    {
        $access = $this->lib->adminAccess();
        if ($access instanceof \Illuminate\Http\RedirectResponse) {
            return $access;
        }

        $userID = $request->input('userID');
        $data = $request->validate([
            'userID' => [ 'required', 'integer' ],
            'name' => [ 'required', 'string' ],
            'phonenumber' => [
                'required',
                'digits:10'
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                Rule::unique('users')->ignore($userID, 'userID')
            ],
            'department' => [ 'required', 'integer' ],
            'position' => [ 'required', 'integer' ],
            'superior' => [ 'required', 'integer' ]
        ]);

        $data['password'] = Hash::make(substr($data['phonenumber'], 4, 6));
        $result = $this->user->update($data);
        return redirect()->route('userManagement.list');
    }

    public function delete(Request $request)
    {
        $access = $this->lib->adminAccess();
        if ($access instanceof \Illuminate\Http\RedirectResponse) {
            return $access;
        }

        $data = $request->validate([
            'userID' => [ 'required', 'integer' ]
        ]);
        $result = $this->user->delete($data['userID']);

        echo($result);
    }
}
