<?php

namespace app\index\controller;

use app\common\model\User;
use think\Controller;

class BaseController extends Controller
{

    /*   protected function checkLoginStatus()
    {
        global $uinfo;
        if (!session("uid"))
            return;
        if (session("loginTime") + 604800 < time())
            session(null);
        else if (!$uinfo = User::get(["id" => session("uid")]))
            session(null);
    }*/

    public function __construct($request = null)
    {
        parent::__construct($request);
       // $this->checkLoginStatus();
    }
}
