<?php

namespace App\Http\Resources;

use App\Models\Department;

class DepartmentResource
{
    public function list()
    {
        $result = Department::get();

        return $result;
    }

    public function store($data)
    {
        $result = Department::insert([
                    'department' => $data['department']
                ]);

        return $result;
    }

    public function update($data)
    {
        $result = Department::where('departmentID', $data['departmentID'])
                    ->update([
                        'department' => $data['department']
                    ]);

        return $result;
    }
}
