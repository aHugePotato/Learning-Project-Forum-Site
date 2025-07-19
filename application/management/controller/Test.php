<?php

namespace app\management\controller;

use app\management\model\Admins;
use think\Controller;

class Test extends Controller
{

    public function index()
    {
        dump(Admins::get(1)->roles());
    }
}
