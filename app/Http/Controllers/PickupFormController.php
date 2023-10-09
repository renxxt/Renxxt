<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\PickupFormResource AS PickupForm;

class PickupFormController extends Controller
{
    protected $pickupForm;

    public function __construct(PickupForm $pickupForm)
    {
        $this->PickupForm = $pickupForm;
    }

    public function list(Request $request)
    {
        $data = $request->validate([
            'attributeID' => [
                'required',
                'integer'
            ]
        ]);

        $pickupData = $this->PickupForm->list($data['attributeID']);
        return $pickupData;
    }
}
