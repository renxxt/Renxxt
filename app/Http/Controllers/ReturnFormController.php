<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ReturnFormResource AS ReturnForm;

class ReturnFormController extends Controller
{
    protected $returnForm;

    public function __construct(ReturnForm $returnForm)
    {
        $this->ReturnForm = $returnForm;
    }

    public function list(Request $request)
    {
        $data = $request->validate([
            'attributeID' => [
                'required',
                'integer'
            ]
        ]);

        $returnData = $this->ReturnForm->list($data['attributeID']);
        return $returnData;
    }
}
