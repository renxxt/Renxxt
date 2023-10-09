<?php

namespace App\Http\Resources;

use App\Models\PickupForm;

class PickupFormResource
{
    public function list($id)
    {
        $result = PickupForm::where('attributeID', $id)
                    ->leftjoin('questions AS Q', 'Q.questionID', '=', 'pickupform.questionID')
                    ->get();

        return $result;
    }

    public function store($data)
    {
        $result = PickupForm::insert([
                    'attributeID' => $data['attributeID'],
                    'questionID' => $data['questionID'],
                    'order' => $data['order'],
                    'required' => $data['required']
        ]);

        return $result;
    }

    public function show($id)
    {
        $result = PickupForm::where('attributeID', $id)
                    ->leftjoin('questions as q', 'q.questionID', '=', 'pickupform.questionID')
                    ->orderBy('order')
                    ->get();

        return $result;
    }

    public function delete($id)
    {
        $result = PickupForm::where('attributeID', $id)->delete();

        return $result;
    }
}
