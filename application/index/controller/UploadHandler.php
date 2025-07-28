<?php

namespace app\index\controller;

use think\Response;

class UploadHandler extends BaseController
{
    public function index()
    {
        if (!session("uid"))
            return Response::create()->code(403);
        if ($this->request->isPost()) {
            if (!$file = $this->request->file("video"))
                return Response::create()->code(500);
            if (!$file = $file->validate(
                ["size" => 300485760, "ext" => "mp4,mov,avi,mwv,mkv,mpg,mpeg"]
            )->rule("hashGen")->move(ROOT_PATH . 'public' . DS . 'tmp'))
                return Response::create()->code(500);
            return $file->getSaveName();
        }
    }
}
