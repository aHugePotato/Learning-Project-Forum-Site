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
            $vidFileId = input("post.video") ? sanitize_filename(input("post.video")) : null;

            if (input("get.op") == "edit" && input("get.id")) {
                $curPost = $postModel->where("id", input("get.id"))->find();
                if (session("uid") !== $curPost["user_id"])
                    $this->error();

                $saveArr = ["text" => $newPost];

                if (!$vidFileId) {
                    $saveArr["media"] = null;
                    @unlink(ROOT_PATH . 'public' . DS . 'uploads' . DS . $curPost["media"]);
                } else if (
                    $vidFileId
                    && $vidFileId != "mock"
                ) {
                    if (!empty($curPost["media"]))
                        @unlink(ROOT_PATH . 'public' . DS . 'uploads' . DS . $curPost["media"]);
                    if (!@UploadHandler::move_to_permanent($vidFileId))
                        $this->error("操作失败");
                    $saveArr["media"] = date("Ymd") . "/" . $vidFileId;
                }

                if (!$postModel->save($saveArr, ["id" => input("get.id")]))
                    $this->error("操作失败");
                $this->success("成功", "/");
            } else {
                if ($vidFileId && !@UploadHandler::move_to_permanent($vidFileId))
                    $this->error("操作失败");
                if (!$postModel->save([
                    "text" => $newPost,
                    "user_id" => session("uid"),
                    "post_id" => input("get.replyTo") ? input("get.replyTo") : null,
                    "media" => $vidFileId ?  date("Ymd") . "/" . $vidFileId : null
                ]))
                    $this->error("操作失败");

                $this->success("成功", "/");
            }
        } else if ($this->request->isGet()) {
            if (input("get.replyTo")) {
                $post = Post::with(["user" => function ($q) {
                    $q->field("id,name");
                }])
                    ->where("id", input("get.replyTo"))
                    ->field("id,update_time,user_id")
                    ->find();
                if (!$post)
                    return $this->error("对象不存在。");
                $this->assign("replyToPost", $post);
            } else if (input("get.op") == "edit" && input("get.id")) {
                $post = $postModel->where("id", input("get.id"))->find();
                $this->assign("editContent", $post["text"]);
                if (!empty($post["media"]))
                    $this->assign("editVidInfo", [
                        "source" => "mock",
                        "name" => explode("/", $post["media"])[1],
                        "size" => filesize(ROOT_PATH . 'public' . DS . 'uploads' . DS . $post["media"]),
                        "type" => "video/" . pathinfo($post["media"], PATHINFO_EXTENSION)
                    ]);
                $this->assign("postUpdateId", input("get.id"));
            }

            global $uinfo;
            if (!empty($uinfo))
                $this->assign("uinfo", $uinfo);

            $postList = (new Post())
                ->alias("a")
                ->join("tp_user b", "a.user_id = b.id")
                ->join("tp_post c", "a.post_id = c.id", "left")
                ->join("tp_user d", "c.user_id = d.id", "left")
                ->field("a.id, a.text, a.create_time, a.update_time, a.user_id, a.media, a.post_id,
                 b.id as user_id, b.name as user_name")
                ->field("c.id as reply_to_id, FROM_UNIXTIME(c.update_time) as reply_to_update_time,
                 d.name as reply_to_user_name, d.name as reply_to_user_name")
                ->order("update_time", "desc")
                ->paginate(15);
            $this->assign("posts", $postList);
            return view();
        }
    }

    public function delete()
    {
        if ($this->request->isPost() && input("get.id")) {
            if (!(new Validate(["__token__" => "require|token"]))->check(input("post.")) || !session("uid"))
                $this->error("/");
            if (!$post = Post::get(input("get.id")))
                return $this->error("操作失败");
            if (!empty($post["media"]))
                @unlink(ROOT_PATH . 'public' . DS . 'uploads' . DS . $post["media"]);
            if (!$post->delete())
                return $this->error("操作失败");
            $this->success("成功", "/");
        }
        return view();
    }

    public function ajax_img_upload()
    {
        if (!session("uid"))
            return Response::create()->code(403);
        if (empty($_FILES))
            return Response::create()->code(400);
        if (!$file = $this->request->file(array_keys($_FILES)[0]))
            return Response::create()->code(500);
        if (!$file = $file->validate(["size" => 10485760, "ext" => "jpg,png,gif,jpeg,bmp,ico,jfif,svg"])
            ->move(ROOT_PATH . 'public' . DS . 'uploads'))
            return Response::create()->code(500);
        return json(["location" => "/uploads/" . str_replace("\\", "/", $file->getSaveName())]);
    }

    /**
     * deprecated
     * 检查有无上传视频，有则保存
     * 
     * @param string $name
     *
     * @return bool|int|string
     */
    protected function save_uploaded_video(string $name)
    {
        if (!session("uid"))
            return Response::create()->code(403);
        if (empty($_FILES[$name]) || $_FILES[$name]["error"] == UPLOAD_ERR_NO_FILE)
            return false;
        if (!$file = $this->request->file($name))
            return 500;
        if (!$file = $file->validate(["size" => 300485760, "ext" => "mp4,mov,avi,mwv,mkv,mpg,mpeg"])
            ->move(ROOT_PATH . 'public' . DS . 'uploads'))
            return 500;
        return "/uploads/" . str_replace("\\", "/", $file->getSaveName());
    }
}
