<?php

namespace app\index\controller;

use think\Controller;
use app\common\model\User;
use app\management\model\Admin;
use think\Hook;

class Test extends BaseController
{
    public function _initialize()
    {
        //  Hook::add('action_begin', 'app\\index\\behavior\\Test');
    }

    private static function f()
    {
        return;
    }

    public function index()
    {
        $a = [1, 2,  4, 5, 6, 7];
        $b = [2, 3,  5, 6, 7, 8, 9];

        return json(uq_array_diff_bi($a, $b));
    }
}
