<?php

namespace app\index\controller;

use app\common\model\Users;
use think\Validate;
use think\Session;

class User extends BaseController
{
    public function signup()
    {
        if ($this->request->isPost()) {
            $usersModel = new Users();
            $vali = new Validate(["__token__" => "token", "name" => "require|max:200", "email" => "require|email", "password" => "require|max:30|min:5"]);
            if (!$vali->check(input("post.")))
                $this->error("请检查输入。");
            $usersModel->save(["name" => input("post.name"), "email" => input("post.email"), "hash" => password_hash(input("post.password"), PASSWORD_BCRYPT)]);
            session("uid", $usersModel->id);
            $this->success("注册成功。", "/");
        }
        return view();
    }

    public function logout()
    {
        session(null);
        $this->success("登出成功", "/");
    }
    public function login()
    {
        if ($this->request->isPost()) {
            $vali = new Validate(["__token__" => "token", "email" => "require|email", "password" => "require|max:30|min:5"]);
            if (!$vali->check(input("post.")))
                $this->error("请检查输入。");
            $user = (new Users())->where("email", input("post.email"))->find();
            if (!$user)
                $this->error("用户不存在。");
            if (!password_verify(input("post.password"), $user["hash"]))
                $this->error("密码错误。");
            session(null);
            session_regenerate_id();
            session("uid", $user["id"]);
            session("loginTime", time());
            $this->success("登入成功。", "/");
        } else
            return view("login");
    }
}
