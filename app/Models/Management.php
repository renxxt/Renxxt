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
        $result = DB::table('staffs AS S')
                    ->select('S.*', 'D.department', 'P.position')
                    ->leftJoin('departments AS D', 'D.departmentID', '=', 'S.departmentID')
                    ->leftJoin('positions AS P', 'P.positionID', '=', 'S.positionID')
                    ->where('state', '0')
                    ->get();

        return $result;
    }

    public function userList($departmentID)
    {
        $result = DB::table('staffs AS S')
                    ->select('S.*', 'D.department', 'P.position')
                    ->leftJoin('departments AS D', 'D.departmentID', '=', 'S.departmentID')
                    ->leftJoin('positions AS P', 'P.positionID', '=', 'S.positionID')
                    ->where('S.departmentID', $departmentID)
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
        $result = DB::table('staffs')->insert([
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

    public function editUser($staffID)
    {
        $result = DB::table('staffs')
                    ->where('staffID', $staffID)
                    ->get();

        if ($result->count() > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function updateUser($data)
    {
        $result = DB::table('staffs')
                    ->where('staffID', $data['staffID'])
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

    public function deleteUser($staffID)
    {
        $result = DB::table('staffs')
                    ->where('staffID', $staffID)
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
                    ->get();

        if ($result->count() > 0) {
            return $result;
        } else {
            return false;
        }
    }
}
