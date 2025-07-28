<?php

namespace app\management\controller;

use app\management\model\Admin as AdminModel;
use think\Validate;
use think\Session;

class AdminAuth extends BaseController
{
    public function signup()
    {
        if ($this->request->isPost()) {
            $adminModel = new AdminModel();
            $vali = new Validate(["__token__|require" => "token", "name" => "require|max:200", "email" => "email", "password" => "require|max:30|min:5"]);
            if (!$vali->check(input("post.")))
                $this->error("请检查输入。");
            $adminModel->save(["name" => input("post.name"), "email" => input("post.email"), "hash" => password_hash(input("post.password"), PASSWORD_BCRYPT)]);
            session(null);
            session_regenerate_id();
            session("aid", $adminModel->id);
            session("loginTime", time());
            $this->success("注册成功。", "/management/posts");
        }
        return view();
    }

    public function logout()
    {
        session(null);
        $this->success("登出成功", "/management/admin_auth/login");
    }

    public function login()
    {
        if ($this->request->isPost()) {
            $vali = new Validate(["__token__" => "token|require", "name" => "require", "password" => "require|max:30|min:5"]);
            if (!$vali->check(input("post.")))
                $this->error("请检查输入。");
            $admin = (new AdminModel())->where("name", input("post.name"))->find();
            if (!$admin)
                $this->error("用户不存在。");
            if($admin["disabled"])
                $this->error("账户已禁用。");
            if (!password_verify(input("post.password"), $admin["hash"]))
                $this->error("密码错误。");
            session(null);
            session_regenerate_id();
            session("aid", $admin["id"]);
            session("loginTime", time());
            $this->success("登入成功。", "/management/posts");
        } else
            return view("login");
    }
}
