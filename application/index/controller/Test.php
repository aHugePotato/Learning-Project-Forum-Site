<?php

namespace app\index\controller;

use think\Controller;
use app\common\model\User;
use app\management\model\Admin;
use think\Hook;
use think\Response;

class Test extends BaseController
{
    public function _initialize()
    {
        //  Hook::add('action_begin', 'app\\index\\behavior\\Test');
    }

    private static function f()
    {
    }

    public function index()
    {
        return strtotime("2002%1%1");
    }
}
