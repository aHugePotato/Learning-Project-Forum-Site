<?php

namespace app\management\controller;

use app\common\model\Post;
use think\Validate;

class Posts extends BaseController
{
    public function _initialize()
    {
        if (!session("aid"))
            $this->error("请先登入。", "/management/admin_auth/login");
        if (empty(get_admin_role(session("aid"))))
            $this->error("权限不足。", "/management/admin_auth/login");
        global $ainfo;
        $this->assign("ainfo", $ainfo);
    }

    public function index()
    {
        $tableData = Post::with(["user" => function ($q) {
            $q->field("id,name");
        }])
            ->fieldRaw("id,SUBSTRING(text,1,99) as text,create_time,update_time,delete_time,user_id,media,post_id")
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
        $data = (new Post())
            ->alias("a")
            ->join("tp_user b", "a.user_id = b.id")
            ->join("tp_post c", "a.post_id = c.id", "left")
            ->join("tp_user d", "c.user_id = d.id", "left")
            ->where("a.id", $id)
            ->field("a.id, a.text, a.create_time, a.update_time, a.delete_time, a.user_id, a.media, a.post_id,
                 b.id as user_id, b.name as user_name")
            ->field("c.id as reply_to_id, FROM_UNIXTIME(c.update_time) as reply_to_update_time,
                 d.name as reply_to_user_name, d.name as reply_to_user_name")
            ->find();
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
        if (!$post = Post::get(input("get.id")))
            return $this->error("操作失败");
        if (!$post->delete())
            return $this->error("操作失败");
        @unlink(ROOT_PATH . 'public' . DS . 'uploads' . DS . $post["media"]);
        $this->success("成功", "/management/posts");
    }
}
