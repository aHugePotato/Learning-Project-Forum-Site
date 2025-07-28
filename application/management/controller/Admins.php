<?php

namespace app\management\controller;

use app\management\model\Admin;
use app\management\model\Role;
use think\Validate;

class Admins extends BaseController
{
    public function _initialize()
    {
        if (!session("aid"))
            $this->error("请先登入。", "/management/admin_auth/login");
        if (empty(check_permission(session("aid"), "view_admins")))
            $this->error("权限不足。", "/management/admin_auth/login");
        global $ainfo;
        $this->assign("ainfo", $ainfo);
    }

    public function index()
    {
        $tableData = Admin::with("role")
            ->field("id,name,email,description,create_time,disabled")->paginate(10);
        // return json($tableData->toArray());
        $this->assign("tableData", $tableData);

        $tableSum = [["count" => Admin::count()]];
        //return json($tableSum);
        $this->assign("tableSum", $tableSum);
        return view();
    }

    public function details($id)
    {
        $data = Admin::with("role.permission")
            ->field("id,name,email,description,create_time,disabled")->where("id", $id)->find();
        if (empty($data))
            $this->error("未找到。");

        $this->assign("data", $data);
        $this->assign("allRoles", Role::all());
        // return json($data);
        return view();
    }

    public function edit()
    {
        if (
            !($adminId = input("get.id")) ||
            !$this->request->isPost() ||
            !(new Validate(["__token__" => "require|token"]))->check(input("post.")) ||
            !check_permission(session("aid"), "edit_admin")
        )
            $this->error("请检查输入");

        if (!$admin = Admin::get($adminId))
            $this->error("未找到");

        $admin->save(["description" => input("post.description"), "disabled" => input("disable") ? 1 : 0]);

        $ogRoles = array_map(
            function ($arg) {
                return (string)$arg["id"];
            },
            Admin::with("role")->where("id", $adminId)->field("id")->find()->toArray()["role"]
        );
        $diff = uq_array_diff_bi($ogRoles, $_POST["roles"]);
        if (!empty($diff["deleted"]))
            $admin->role()->detach($diff["deleted"]);
        if (!empty($diff["added"]))
            $admin->role()->attach($diff["added"]);

        $this->success("成功", "/management/admins");
    }
}
