<?php

namespace app\index\controller;

use think\Controller;
use app\common\model\Users;

class Test extends Controller
{
    private static function f()
    {
        return;
    }

    public function index() {
      // return json(Testbs::with("tests")->select());
       //return json(Posts::with(["users"=>function($query){$query->field('id,email');}])->select());
       return json(Users::get(9));
    }
}
