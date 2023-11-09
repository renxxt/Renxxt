<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Lib;
use Illuminate\Validation\Rule;
use App\Http\Resources\PositionResource AS Position;
use App\Models\Position AS PositionModel;

class PositionController extends Controller
{
    protected $lib;
    protected $position;

    public function __construct(Lib $lib, Position $position)
    {
        $this->lib = $lib;
        $this->position = $position;
    }

    public function list()
    {
        $access = $this->lib->adminAccess();
        if ($access instanceof \Illuminate\Http\RedirectResponse) {
            return $access;
        }

        $result = $this->position->list();
        return view('positionManagement.positionList', ['result' => $result]);
    }

    public function store(Request $request)
    {
        $access = $this->lib->adminAccess();
        if ($access instanceof \Illuminate\Http\RedirectResponse) {
            return $access;
        }

        $data = $request->validate([
            'position' => [
                'required',
                'string',
                Rule::unique('positions')
            ]
        ]);

        $maxOrder = $this->position->getMaxOrder();
        $data['order'] = $maxOrder+1;
        $result = $this->position->store($data);

        return $result;
    }

    public function changeOrder(Request $request)
    {
        $data = $request->validate([
            'sortedItems' => [
                'required',
                'array'
            ],
            'sortedItems.*.positionID' => [
                'required',
                'integer'
            ],
            'sortedItems.*.position' => [
                'required',
                'string'
            ],
            'sortedItems.*.order' => [
                'required',
                'integer'
            ]
        ]);

        $positions = array();
        foreach ($data['sortedItems'] as $row) {
            $positions[] = [ 'positionID' => $row['positionID'], 'position' => $row['position'], 'order' => $row['order'] ];
        }

        $model = new PositionModel();
        $result = $model->upsert($positions, ['positionID'], ['order']);

        return $result;
    }
}
