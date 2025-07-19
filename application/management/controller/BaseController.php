<?php

namespace app\management\controller;

use app\common\model\User;
use think\Controller;

class BaseController extends Controller
{

    protected function checkLoginStatus()
    {
        if (!session("aid"))
            return;
        if (session("loginTime") + 604800 < time())
            session(null);
        else if (!User::get(["id"=>session("aid")]))
            session(null);
    }

    public function __construct($request = null)
    {
        parent::__construct($request);
        $this->checkLoginStatus();
    }
}
