<?php

namespace App\Libraries;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;

class Lib
{
    protected $maxOrder;

    public function __construct()
    {
        $this->maxOrder = DB::table('positions')->max('order');
        View::share('maxOrder', $this->maxOrder);
    }

    protected static function getFacadeAccessor()
    {
        return 'lib';
    }

    # 最高管理員
    public function adminAccess()
    {
        if (session('order') == 1) {
            //
        } else {
            return redirect()->route('renxxt');
        }
    }

    # 管理層
    public function managementAccess()
    {
        if (session('order') < $this->maxOrder) {
            //
        } else {
            return redirect()->route('renxxt');
        }
    }

    # 一般使用者
    public function userAccess()
    {
        if (session('order') > 1) {
            //
        } else {
            return redirect()->route('renxxt');
        }
    }
}
?>
