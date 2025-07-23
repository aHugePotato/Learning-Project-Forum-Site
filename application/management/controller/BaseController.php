<?php

namespace app\management\controller;

use app\management\model\Admin;
use think\Controller;

class BaseController extends Controller
{

    protected function checkLoginStatus()
    {
        global $ainfo;
        if (!session("aid"))
            return;
        if (session("loginTime") + 604800 < time())
            session(null);
        else if (!$ainfo = Admin::get(["id"=>session("aid")]))
            session(null);
    }

    public function __construct($request = null)
    {
        parent::__construct($request);
        $this->checkLoginStatus();
    }
}
