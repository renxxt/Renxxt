<?php

namespace App\Http\Resources;

use App\Models\User;

class UserResource
{
    public function list($data)
    {
        if (isset($data['order'])) {
            $result = User::select('userID', 'name', 'P.position', 'P.order')
                        ->leftJoin('positions AS P', 'P.positionID', '=', 'users.positionID')
                        ->where('P.order', '<', $data['order'])
                        ->where('state', '0')
                        ->get();
        } elseif (isset($data['departmentID']) && $data['departmentID'] > 0) {
            $result = User::select('users.*', 'U.name AS superior', 'D.department', 'P.position')
                        ->leftJoin('users AS U', 'users.superiorID', '=', 'U.userID')
                        ->leftJoin('departments AS D', 'D.departmentID', '=', 'users.departmentID')
                        ->leftJoin('positions AS P', 'P.positionID', '=', 'users.positionID')
                        ->where('users.departmentID', $data['departmentID'])
                        ->where('users.state', '0')
                        ->get();
        } else {
            $result = User::select('users.*', 'U.name AS superior', 'D.department', 'P.position')
                        ->leftJoin('users AS U', 'users.superiorID', '=', 'U.userID')
                        ->leftJoin('departments AS D', 'D.departmentID', '=', 'users.departmentID')
                        ->leftJoin('positions AS P', 'P.positionID', '=', 'users.positionID')
                        ->where('users.state', 0)
                        ->distinct()
                        ->get();
        }

        return $result;
    }

    public function store($data)
    {
        $result = User::insert([
            'uid' => $data['uid'],
            'name' => $data['name'],
            'phonenumber' => $data['phonenumber'],
            'email' => $data['email'],
            'password' => $data['password'],
            'departmentID' => $data['department'],
            'positionID' => $data['position'],
            'superiorID' => $data['superior'],
            'state' => '0'
        ]);

        return $result;
    }

    public function show($id)
    {
        $result = User::where('userID', $id)->first();

        return $result;
    }

    public function update($data)
    {
        $result = User::where('userID', $data['userID'])
                    ->update([
                        'name' => $data['name'],
                        'phonenumber' => $data['phonenumber'],
                        'email' => $data['email'],
                        'password' => $data['password'],
                        'departmentID' => $data['department'],
                        'positionID' => $data['position'],
                        'superiorID' => $data['superior'],
                        'state' => '0'
                    ]);

        return $result;
    }

    public function delete($id)
    {
        $result = User::where('userID', $id)
                    ->update([
                        'state' => 1
                    ]);

        return $result;
    }

    public function getId($uid)
    {
        $result = User::where('uid', $uid)
                    ->select('userID')
                    ->first();

        return $result;
    }
}
