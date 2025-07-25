<?php

namespace app\management\controller;

use app\common\model\Post;
use think\Validate;

class Posts extends BaseController
{
    public function _initialize()
    {
        if (!session("aid"))
            $this->error("请先登入。", "/management/adminauth/login");
        if (empty(get_admin_role(session("aid"))))
            $this->error("权限不足。", "/management/adminauth/login");
        global $ainfo;
        $this->assign("ainfo", $ainfo);
    }

    public function index()
    {
        $tableData = Post::with(["user" => function ($q) {
            $q->field("id,name");
        }])
            ->fieldRaw("id,SUBSTRING(text,1,99) as text,create_time,update_time,delete_time,user_id,media")
            ->order(["update_time" => "desc"])->paginate(30);
        //return json($tableData);
        $this->assign("tableData", $tableData);

        $tableSum = [["count" => Post::count()]];
        //return json($tableSum);
        $this->assign("tableSum", $tableSum);
        return view();
    }

    public function details($id)
    {
        $data = Post::with(["user" => function ($q) {
            $q->field("id,name");
        }])->where("id", $id)->find();
        if (empty($data))
            $this->error("未找到。");

        $this->assign("data", $data);
        //return json($data);
        return view();
    }

    public function delete()
    {
        if ($this->request->isGet())
            return view(null, ["hard" => input("get.hard") == "true"]);

        if (
            !(new Validate(["__token__" => "require|token"]))->check(input("post.")) ||
            !check_permission(session("aid"), "delete_post")
        )
            $this->error("请检查输入");
        if (($isHard = (input("get.hard") == "true")) && !check_permission(session("aid"), "delete_post_perm"))
            $this->error();
        if (!Post::destroy(input("get.id"), $isHard))
            return $this->error("操作失败");
        $this->success("成功", "/management/posts");
    }
}
