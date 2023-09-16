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
}
