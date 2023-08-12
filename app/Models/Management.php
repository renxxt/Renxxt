<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Management extends Model
{
    use HasFactory;

    public function allUserList()
    {
        $result = DB::table('users AS U')
                    ->select('U.*', 'D.department', 'P.position')
                    ->leftJoin('departments AS D', 'D.departmentID', '=', 'U.departmentID')
                    ->leftJoin('positions AS P', 'P.positionID', '=', 'U.positionID')
                    ->where('U.userID', '>', '1')
                    ->where('state', '0')
                    ->get();

        return $result;
    }

    public function userList($departmentID)
    {
        $result = DB::table('users AS U')
                    ->select('U.*', 'D.department', 'P.position')
                    ->leftJoin('departments AS D', 'D.departmentID', '=', 'U.departmentID')
                    ->leftJoin('positions AS P', 'P.positionID', '=', 'U.positionID')
                    ->where('U.userID', '>', '1')
                    ->where('U.departmentID', $departmentID)
                    ->where('state', '0')
                    ->get();

        if ($result->count() > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function createUser($data)
    {
        $result = DB::table('users')->insert([
                    'name' => $data['name'],
                    'uid' => $data['uid'],
                    'phonenumber' => $data['phonenumber'],
                    'email' => $data['email'],
                    'password' => $data['password'],
                    'departmentID' => $data['department'],
                    'positionID' => $data['position'],
                    'state' => '0'
                ]);

        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function editUser($userID)
    {
        $result = DB::table('users')
                    ->where('userID', $userID)
                    ->get();

        if ($result->count() > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function updateUser($data)
    {
        $result = DB::table('users')
                    ->where('userID', $data['userID'])
                    ->update([
                        'name' => $data['name'],
                        'departmentID' => $data['department'],
                        'email' => $data['email'],
                        'phonenumber' => $data['phonenumber'],
                        'positionID' => $data['position']
                    ]);

        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteUser($userID)
    {
        $result = DB::table('users')
                    ->where('userID', $userID)
                    ->update(['state' => 1]);

        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getDepartments()
    {
        $result = DB::table('departments')->get();

        if ($result->count() > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function getPositions()
    {
        $result = DB::table('positions')
                    ->select('positionID', 'position')
                    ->where('positionID', '>', '1')
                    ->get();

        if ($result->count() > 0) {
            return $result;
        } else {
            return false;
        }
    }
}
