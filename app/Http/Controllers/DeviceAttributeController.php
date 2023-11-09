<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Lib;
use Illuminate\Validation\Rule;
use App\Http\Resources\DeviceAttributeResource AS DeviceAttribute;
use App\Http\Resources\PickupFormResource AS PickupForm;
use App\Http\Resources\ReturnFormResource AS ReturnForm;

class DeviceAttributeController extends Controller
{
    protected $lib;
    protected $deviceAttribute;

    public function __construct(Lib $lib, DeviceAttribute $deviceAttribute)
    {
        $this->lib = $lib;
        $this->deviceAttribute = $deviceAttribute;
    }

    public function list()
    {
        $access = $this->lib->adminAccess();
        if ($access instanceof \Illuminate\Http\RedirectResponse) {
            return $access;
        }
        $attributes = $this->deviceAttribute->managementList();

        return view('serviceManagement.serviceManagement', ['attributes' => $attributes]);
    }

    public function create()
    {
        $access = $this->lib->adminAccess();
        if ($access instanceof \Illuminate\Http\RedirectResponse) {
            return $access;
        }

        return view('serviceManagement.createAttribute');
    }

    public function store(Request $request)
    {
        $access = $this->lib->adminAccess();
        if ($access instanceof \Illuminate\Http\RedirectResponse) {
            return $access;
        }

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('deviceattributes')
            ],
            'display' => [ 'string' ],
            'approved_layers' => [ 'required', 'integer' ],
            'approved_level' => [ 'required', 'integer' ],
            'pickup_form' => [ 'string' ],
            'return_form' => [ 'string' ],
            'companion_number' => [ 'required', 'integer' ]
        ]);
        $data['display'] = (isset($data['display']) && $data['display'] == 'on') ? 1 : 0;
        $data['pickup_form'] = (isset($data['pickup_form']) && $data['pickup_form'] == 'on') ? 1 : 0;
        $data['return_form'] = (isset($data['return_form']) && $data['return_form'] == 'on') ? 1 : 0;

        $pickup = $request->validate([
            'pickupQuestion' => [
                'required_if:pickup_form,on',
                'array'
            ],
            'pickupRequired' => [
                'required_if:pickup_form,on',
                'array'
            ]
        ]);

        $return = $request->validate([
            'returnQuestion' => [
                'required_if:return_form,on',
                'array'
            ],
            'returnRequired' => [
                'required_if:return_form,on',
                'array'
            ]
        ]);

        $attributeID = $this->deviceAttribute->store($data);

        if ($data['pickup_form'] == 1) {
            $pickupData = array_combine($pickup['pickupQuestion'], $pickup['pickupRequired']);
            $pickupFormResource = new PickupForm();
            $order = 0;
            foreach ($pickupData as $questionID => $required) {
                $order++;
                $required = ($required === true) ? 0 : 1;
                $question = [
                    'attributeID' => $attributeID,
                    'questionID' => $questionID,
                    'order' => $order,
                    'required' => $required
                ];
                $pickupFormResource->store($question);
            }
        }

        if ($data['return_form'] == 1) {
            $returnData = array_combine($return['returnQuestion'], $return['returnRequired']);
            $returnFormResource = new ReturnForm();
            $order = 0;
            foreach ($returnData as $questionID => $required) {
                $order++;
                $required = ($required === true) ? 0 : 1;
                $question = [
                    'attributeID' => $attributeID,
                    'questionID' => $questionID,
                    'order' => $order,
                    'required' => $required
                ];
                $returnFormResource->store($question);
            }
        }

        return redirect()->route('serviceManagement.list');
    }

    public function show($id)
    {
        $access = $this->lib->adminAccess();
        if ($access instanceof \Illuminate\Http\RedirectResponse) {
            return $access;
        }

        $result = $this->deviceAttribute->show($id);
        if (!$result) {
            $messageData = [
                'type' => "danger",
                'message' => "無該設備類別"
            ];
            return redirect()->route('serviceManagement.list')->with('messageData', [$messageData]);
        }

        return view('serviceManagement.editAttribute', ['result' => $result]);
    }

    public function update(Request $request)
    {
        $access = $this->lib->adminAccess();
        if ($access instanceof \Illuminate\Http\RedirectResponse) {
            return $access;
        }

        $attributeID = $request->input('attributeID');
        $data = $request->validate([
            'attributeID' => [
                'required',
                'integer'
            ],
            'name' => [
                'required',
                'string',
                Rule::unique('deviceattributes')->ignore($attributeID, 'attributeID')
            ],
            'display' => [ 'string' ],
            'approved_layers' => [ 'required', 'integer' ],
            'approved_level' => [ 'required', 'integer' ],
            'pickup_form' => [ 'string' ],
            'return_form' => [ 'string' ],
            'companion_number' => [ 'required', 'integer' ]
        ]);
        $data['display'] = (isset($data['display']) && $data['display'] == 'on') ? 1 : 0;
        $data['pickup_form'] = (isset($data['pickup_form']) && $data['pickup_form'] == 'on') ? 1 : 0;
        $data['return_form'] = (isset($data['return_form']) && $data['return_form'] == 'on') ? 1 : 0;

        $pickup = $request->validate([
            'pickupQuestion' => [
                'required_if:pickup_form,on',
                'array'
            ],
            'pickupRequired' => [
                'required_if:pickup_form,on',
                'array'
            ]
        ]);

        $return = $request->validate([
            'returnQuestion' => [
                'required_if:return_form,on',
                'array'
            ],
            'returnRequired' => [
                'required_if:return_form,on',
                'array'
            ]
        ]);

        $attributeID = $this->deviceAttribute->update($data);
        $pickupFormResource = new PickupForm();
        $pickupFormResource->delete($data['attributeID']);
        $returnFormResource = new ReturnForm();
        $returnFormResource->delete($data['attributeID']);

        if ($data['pickup_form'] == 1) {
            $pickupData = array_combine($pickup['pickupQuestion'], $pickup['pickupRequired']);
            $order = 0;
            foreach ($pickupData as $questionID => $required) {
                $order++;
                $required = ($required === true) ? 0 : 1;
                $question = [
                    'attributeID' => $data['attributeID'],
                    'questionID' => $questionID,
                    'order' => $order,
                    'required' => $required
                ];
                $pickupFormResource->store($question);
            }
        }

        if ($data['return_form'] == 1) {
            $returnData = array_combine($return['returnQuestion'], $return['returnRequired']);
            $order = 0;
            foreach ($returnData as $questionID => $required) {
                $order++;
                $required = ($required === true) ? 0 : 1;
                $question = [
                    'attributeID' => $data['attributeID'],
                    'questionID' => $questionID,
                    'order' => $order,
                    'required' => $required
                ];
                $returnFormResource->store($question);
            }
        }

        return redirect()->route('serviceManagement.list');
    }

    public function delete(Request $request)
    {
        $access = $this->lib->adminAccess();
        if ($access instanceof \Illuminate\Http\RedirectResponse) {
            return $access;
        }

        $data = $request->validate([
            'id' => [ 'required', 'integer' ]
        ]);
        $result = $this->deviceAttribute->delete($data['id']);

        echo($result);
    }

    public function changeDisplay(Request $request)
    {
        $access = $this->lib->adminAccess();
        if ($access instanceof \Illuminate\Http\RedirectResponse) {
            return $access;
        }

        $data = $request->validate([
            'attributeID' => [ 'required', 'integer' ],
            'display' => [ 'required', 'integer' ]
        ]);

        $result = $this->deviceAttribute->changeDisplay($data);
        return $result;
    }
}
