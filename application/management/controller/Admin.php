<?php

namespace app\management\controller;

use app\common\model\Users;
use think\Validate;
use think\Session;

class Admin extends BaseController
{

    public function logout()
    {
        session(null);
        $this->success("登出成功", "/");
    }

    public function login()
    {
        if ($this->request->isPost()) {
            $vali = new Validate(["__token__" => "token", "name" => "require", "password" => "require|max:30|min:5"]);
            if (!$vali->check(input("post.")))
                $this->error("请检查输入。");
            $user = (new Users())->where("name", input("post.name"))->find();
            if (!$user)
                $this->error("用户不存在。");
            if (!password_verify(input("post.password"), $user["hash"]))
                $this->error("密码错误。");
            session(null);
            session_regenerate_id();
            session("aid", $user["id"]);
            session("loginTime", time());
            $this->success("登入成功。", "/");
        } else
            return view("login");
    }
}
