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
                    ->orderBy('order', 'desc')
                    ->get();

        if ($result->count() > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function attributeList()
    {
        $result = DB::table('deviceattributes')
                    ->where('display', '<', 2)
                    ->get();

        if ($result->count() > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function createAttribute($data)
    {
        $result = DB::table('deviceattributes')
                    ->insertGetId([
                        'name' => $data['name'],
                        'display' => $data['display'],
                        'approved_layers' => $data['approved_layers'],
                        'approved_level' => $data['approved_level'],
                        'pickup_form' => $data['pickup_form'],
                        'return_form' => $data['return_form'],
                        'companion_number' => $data['companion_number']
                    ]);

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    public function createPickupForm($data, $attributeID)
    {
        $result = DB::table('pickupform')
                    ->insert([
                        'attributeID' => $attributeID,
                        'questionID' => $data['questionID'],
                        'order' => $data['order'],
                        'required' => $data['required']
                    ]);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function createReturnForm($data, $attributeID)
    {
        $result = DB::table('returnform')
                    ->insert([
                        'attributeID' => $attributeID,
                        'questionID' => $data['questionID'],
                        'order' => $data['order'],
                        'required' => $data['required']
                    ]);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function editAttribute($attributeID)
    {
        $result = DB::table('deviceattributes')
                    ->where('attributeID', $attributeID)
                    ->first();

        return $result;
    }

    public function updateAttribute($data)
    {
        $result = DB::table('deviceattributes')
                    ->where('attributeID', $data['attributeID'])
                    ->update([
                        'name' => $data['name'],
                        'display' => $data['display'],
                        'approved_layers' => $data['approved_layers'],
                        'approved_level' => $data['approved_level'],
                        'pickup_form' => $data['pickup_form'],
                        'return_form' => $data['return_form'],
                        'companion_number' => $data['companion_number']
                    ]);

        return $result;
    }

    public function deleteAttribute($attributeID)
    {
        $result = DB::table('deviceattributes')
                    ->where('attributeID', $attributeID)
                    ->update([
                        'display' => 2
                    ]);

        DB::table('devices')
            ->where('attributeID', $attributeID)
            ->update([
                'display' => 2
            ]);

        return $result;
    }

    public function deviceList($attributeID)
    {
        $result = DB::table('devices AS D')
                    ->select('D.*', 'U.name')
                    ->leftjoin('users AS U', 'U.userID', '=', 'D.userID')
                    ->where('attributeID', $attributeID)
                    ->where('display', '<', 2)
                    ->get();

        if ($result->count() > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function getAttributes()
    {
        $result = DB::table('deviceattributes')
                    ->where('display', '<', 2)
                    ->select('attributeID', 'name')
                    ->get();

        if ($result->count() > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function createDevice($data)
    {
        $userID = DB::table('users')
                    ->where('uid', $data['uid'])
                    ->select('userID')
                    ->first();

        $result = DB::table('devices')
                    ->insert([
                        'name' => $data['name'],
                        'type' => $data['type'],
                        'userID' => $userID->userID,
                        'storage_location' => $data['storage_location'],
                        'price' => $data['price'],
                        'attributeID' => $data['attributeID'],
                        'display' => $data['display']
                    ]);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function editDevice($deviceID)
    {
        $result = DB::table('devices')
                    ->where('deviceID', $deviceID)
                    ->first();

        return $result;
    }

    public function updateDevice($data)
    {
        $userID = DB::table('users')
                    ->where('uid', $data['uid'])
                    ->select('userID')
                    ->first();

        $result = DB::table('devices')
                    ->where('deviceID', $data['deviceID'])
                    ->update([
                        'name' => $data['name'],
                        'type' => $data['type'],
                        'userID' => $userID->userID,
                        'storage_location' => $data['storage_location'],
                        'price' => $data['price'],
                        'attributeID' => $data['attributeID'],
                        'display' => $data['display']
                    ]);

        return $result;
    }

    public function deleteDevice($deviceID)
    {
        $result = DB::table('devices')
                    ->where('deviceID', $deviceID)
                    ->update([
                        'display' => 2
                    ]);

        return $result;
    }

    public function changeAttributeState($data)
    {
        $result = DB::table('deviceattributes')
                    ->where('attributeID', $data['attributeID'])
                    ->update([
                        'display' => $data['display']
                    ]);

        return $result;
    }

    public function changeDeviceState($data)
    {
        $result = DB::table('devices')
                    ->where('deviceID', $data['deviceID'])
                    ->update([
                        'display' => $data['display']
                    ]);

        return $result;
    }

    public function questionList()
    {
        $result = DB::table('questions AS Q')
                    ->leftjoin('options AS O', 'O.questionID', '=', 'Q.questionID')
                    ->get();

        return $result;
    }

    public function createQuestion($question, $type)
    {
        $result = DB::table('questions')
                    ->insertGetId([
                        'question' => $question,
                        'type' => $type
                    ]);

        return $result;
    }

    public function createOption($data, $questionID)
    {
        $result = DB::table('options')
                    ->insert([
                        'option' => $data['option'],
                        'questionID' => $questionID,
                        'sort' => $data['sort']
                    ]);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}
