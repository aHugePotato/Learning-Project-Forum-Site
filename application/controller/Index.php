<?php

namespace app\controller;

use app\model\Posts;
use app\model\Users;
use HTMLPurifier;
use HTMLPurifier_Config;
use think\Validate;

class Index extends BaseController
{
    public function index()
    {
        $postsModel = new Posts();
        if ($newPost = input("post.newPost", null, null)) {
            if (!session("uid") || !(new Validate(["__token__" => 'token']))->check(input("post.")))
                $this->error();

            $newPost = (new HTMLPurifier(HTMLPurifier_Config::createDefault()))->purify($newPost);
            if (input("get.op") == "edit" && input("get.id")) {
                if (session("uid") !== $postsModel->where("id", input("get.id"))->find()["user_id"])
                    $this->error();
                $postsModel->save(["text" => $newPost], ["id" => input("get.id")]);
                $this->success("成功", "/");
            } else {
                $postsModel->save(["text" => $newPost, "user_id" => session("uid")]);
                $this->success("成功", "/");
            }
        } else if ($this->request->isGet()) {
            if (input("get.op") == "edit" && input("get.id")) {
                $this->assign("editContent", $postsModel->where("id", input("get.id"))->find()["text"]);
                $this->assign("postUpdateId", input("get.id"));
            }

            if (session("uid")) {
                $uinfo = (new Users())->where("id", session("uid"))->find();
                $this->assign("uinfo", $uinfo);
            }

            $postList = Posts::with(["users" => function ($query) {
                $query->field('id,name');
            }])->order("update_time", "desc")->paginate(15);
            $this->assign("posts", $postList);
            return view();
        }
    }

    public function delete($id)
    {
        if ($this->request->isPost() && $id) {
            if (!(new Validate(["__token__" => 'token']))->check(input("param.")))
                $this->error("/");
            Posts::destroy($id);
            $this->success("成功", "/");
        }
        return view();
    }

    public function ajax_img_upload()
    {
        if (empty($_FILES))
            return;
        $img = $this->request->file(array_keys($_FILES)[0]);
    }
}
