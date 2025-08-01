<?php

namespace app\common\controller;

use think\Controller;
use think\Response;

/**
 * class UploadHandler
 * 处理大文件上传
 */
class BaseUploadHandler extends Controller
{
    public static function move_to_permanent($fname)
    {
        $dstPath = ROOT_PATH . 'public' . DS . 'uploads' . DS . date("Ymd") . DS;
        if (!is_dir($dstPath))
            mkdir($dstPath);
        return  rename(
            ROOT_PATH . 'public' . DS . 'tmp' . DS . $fname,
            $dstPath . $fname
        );
    }

    protected function auth()
    {
        return true;
    }

    protected $fileExtensions = "";
    protected $inputName = "file";

    public function index()
    {
        if (!$this->auth())
            return Response::create()->code(403);
        if ($this->request->isPost()) {
            if (!$file = $this->request->file($this->inputName))
                return Response::create()->code(500);
            if (!$file = $file->validate(["size" => 300485760, "ext" => $this->fileExtensions])
                ->rule("hashGen")
                ->move(ROOT_PATH . 'public' . DS . 'tmp'))
                return Response::create()->code(500);
            return $file->getSaveName();
        }
        if ($this->request->isDelete()) {
            $fullPath = ROOT_PATH . 'public' . DS . 'tmp' . DS . sanitize_filename($this->request->getContent());
            if (!file_exists($fullPath))
                return Response::create()->code(400);
            if (file_exists($fullPath) && !@unlink($fullPath))
                return Response::create()->code(500);
            return;
        } //isGet部分在filepond不使用mock时被调用
        if ($this->request->isGet()) {
            $split = explode("/", $this->request->getContent());
            $split = array_map(function ($arg) {
                sanitize_filename($arg);
            }, $split);
            if (count($split) != 2)
                return Response::create()->code(400);

            header('Content-Disposition: inline; filename="' . $split[1] . '"');
            if (!@readfile(ROOT_PATH . 'public' . DS . 'uploads' . DS . $split[0] . DS . $split[1]))
                return Response::create()->code(500);
        }
    }
}
