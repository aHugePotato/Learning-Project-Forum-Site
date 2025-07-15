<?php

namespace app\controller;

use app\model\Users;
use think\Controller;

class BaseController extends Controller
{

    protected function checkLoginStatus()
    {
        if (!session("uid"))
            return;
        if (session("loginTime") + 604800 < time())
            session(null);
        else if (!Users::get(["id"=>session("uid")]))
            session(null);
    }

    public function __construct($request = null)
    {
        parent::__construct($request);
        $this->checkLoginStatus();
    }
}
