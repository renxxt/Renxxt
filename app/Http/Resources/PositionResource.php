<?php

namespace App\Http\Resources;

use App\Models\Position;

class PositionResource
{
    public function list()
    {
        $result = Position::where('positionID', '>', '1')
                    ->orderBy('order', 'asc')
                    ->get();

        return $result;
    }

    public function store($data)
    {
        $result = Position::insert([
                    'position' => $data['position'],
                    'order' => $data['order']
                ]);

        return $result;
    }

    public function getMaxOrder()
    {
        $result = Position::max('order');

        return $result;
    }

    public function getOrder($id)
    {
        $result = Position::where('positionID', $id)
                    ->select('order')
                    ->first();

        return $result;
    }
}
