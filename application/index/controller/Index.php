<?php

namespace app\index\controller;

use app\common\model\Post;
use app\common\model\User;
use HTMLPurifier;
use HTMLPurifier_Config;
use think\Response;
use think\Validate;

class Index extends BaseController
{
    public function index()
    {
        $postModel = new Post();
        if ($this->request->isPost()) {
            if (
                !session("uid") ||
                !(new Validate(["__token__" => "require|token", "newPost" => "require|max:1023"]))->check(input("post."))
            )
                $this->error("请检查输入。");
            $newPost = input("post.newPost", null, null);
            $newPost = (new HTMLPurifier(HTMLPurifier_Config::createDefault()))->purify($newPost);

            if (input("get.op") == "edit" && input("get.id")) {
                if (session("uid") !== $postModel->where("id", input("get.id"))->find()["user_id"])
                    $this->error();
                if (is_int($videoSavePath = $this->save_uploaded_video("video")))
                    $this->error("操作失败");
                !$postModel->save(
                    ["text" => $newPost, "media" => $videoSavePath === false ? null : $videoSavePath],
                    ["id" => input("get.id")]
                );
                $this->success("成功", "/");
            } else {
                if (is_int($videoSavePath = $this->save_uploaded_video("video")))
                    $this->error("操作失败");
                !$postModel->save([
                    "text" => $newPost,
                    "user_id" => session("uid"),
                    "media" => $videoSavePath === false ? null : $videoSavePath
                ]);
                $this->success("成功", "/");
            }
        } else if ($this->request->isGet()) {
            if (input("get.op") == "edit" && input("get.id")) {
                $this->assign("editContent", $postModel->where("id", input("get.id"))->find()["text"]);
                $this->assign("postUpdateId", input("get.id"));
            }

            global $uinfo;
            if (!empty($uinfo))
                $this->assign("uinfo", $uinfo);

            $postList = Post::with(["user" => function ($query) {
                $query->field('id,name');
            }])->order("update_time", "desc")->paginate(15);
            $this->assign("posts", $postList);
            return view();
        }
    }

    public function delete()
    {
        if ($this->request->isPost() && input("get.id")) {
            if (!(new Validate(["__token__" => "require|token"]))->check(input("post.")))
                $this->error("/");
            if (!Post::destroy(input("get.id")))
                return $this->error("操作失败");
            $this->success("成功", "/");
        }
        return view();
    }

    public function ajax_img_upload()
    {
        if (empty($_FILES) || array_values($_FILES)[0]["error"])
            return Response::create()->code(400);
        $file = $this->request->file(array_keys($_FILES)[0]);
        if (!$file = $file->validate(["size" => 10485760, "ext" => "jpg,png,gif,jpeg,bmp,ico,jfif,svg"]))
            return Response::create()->code(400);
        if (!$file = $file->move(ROOT_PATH . 'public' . DS . 'uploads'))
            return Response::create()->code(500);
        return json(["location" => "/uploads/" . str_replace("\\", "/", $file->getSaveName())]);
    }

    /**
     * 检查有无上传视频，有则保存
     *
     * @param string $name
     *
     * @return bool|int|string
     */
    protected function save_uploaded_video(string $name)
    {
        if (empty($_FILES[$name]) || $_FILES[$name]["error"] == UPLOAD_ERR_NO_FILE)
            return false;
        if (!$file = $this->request->file($name))
            return 500;
        if (!$file = $file->validate(["size" => 300485760, "ext" => "mp4,mov,avi,mwv,mkv,mpg,mpeg"]))
            return 400;
        if (!$file = $file->move(ROOT_PATH . 'public' . DS . 'uploads'))
            return 500;
        return "/uploads/" . str_replace("\\", "/", $file->getSaveName());
    }
}
