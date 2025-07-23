<?php

namespace app\index\controller;

use think\Controller;
use app\common\model\User;
use app\management\model\Admin;

class Test extends Controller
{
    private static function f()
    {
        return;
    }

    public function index()
    {
        // return json(Testbs::with("tests")->select());
        //return json(Posts::with(["users"=>function($query){$query->field('id,email');}])->select());
        $a = Admin::get(2);
        $a->hash = password_hash("123456", PASSWORD_BCRYPT);
        $a->save();
    }
}
