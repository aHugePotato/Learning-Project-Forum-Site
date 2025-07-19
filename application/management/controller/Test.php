<?php

namespace app\management\controller;

use app\management\model\Admin;
use think\Controller;

class Test extends Controller
{

    public function index()
    {
      return  json(Admin::get(1)->role);
        return;
    }
}
