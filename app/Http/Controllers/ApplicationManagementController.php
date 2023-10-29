<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Lib;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ApplicationManagementResource AS ApplicationManagement;
use App\Http\Resources\ApprovedApplicationResource AS ApprovedApplication;

class ApplicationManagementController extends Controller
{
    protected $lib;
    protected $applicationManagement;

    public function __construct(Lib $lib, ApplicationManagement $applicationManagement)
    {
        $this->lib = $lib;
        $this->applicationManagement = $applicationManagement;
    }

    public function applicationList()
    {
        $this->lib->adminAccess();
        $userID = Auth::user()->id;
        $result = $this->applicationManagement->applicationList($userID);

        return view('applicationManagement.applicationList', ['result' => $result]);
    }

    public function cancelList()
    {
        $this->lib->adminAccess();
        $userID = Auth::user()->id;
        $result = $this->applicationManagement->cancelList($userID);

        return view('applicationManagement.cancelList', ['result' => $result]);
    }

    public function completedList()
    {
        $this->lib->adminAccess();
        $userID = Auth::user()->id;
        $result = $this->applicationManagement->completedList($userID);

        return view('applicationManagement.completedList', ['result' => $result]);
    }

    public function approve(Request $request)
    {
        $this->lib->adminAccess();
        $data = $request->validate([
            'applicationID' =>  [ 'required', 'integer' ]
        ]);
        $data['userID'] = Auth::user()->id;
        $data['state'] = 1;

        $resource = new ApprovedApplication();
        $resource->store($data);
        $result = $this->applicationManagement->changeState($data);

        echo($result);
    }
}
