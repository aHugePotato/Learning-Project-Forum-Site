<?php

namespace app\management\controller;

use app\common\model\Post;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use think\Validate;

class Posts extends BaseController
{
    public function _initialize()
    {
        if (!session("aid"))
            $this->error("请先登入。", "/management/admin_auth/login");
        if (empty(check_permission(session("aid"), "view_posts")))
            $this->error("权限不足。", "/management/admin_auth/login");
        global $ainfo;
        $this->assign("ainfo", $ainfo);
    }

    public function index()
    {
        $tableData = Post::withTrashed()->alias("a")->join("user b", "a.user_id = b.id")
            ->fieldRaw("a.id as id,SUBSTRING(a.text,1,99) as text,a.create_time as create_time,
            a.update_time as update_time,a.delete_time as delete_time,a.user_id as user_id,media,post_id,b.name as user_name")
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
        $data = Post::withTrashed()->info()->where("a.id", $id)->find();
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
        if (!$post->delete($isHard))
            return $this->error("操作失败");
        @unlink(ROOT_PATH . 'public' . DS . 'uploads' . DS . $post["media"]);
        $this->success("成功", "/management/posts");
    }

    public function export()
    {
        $spreadSheet = new Spreadsheet();
        $spreadSheet->getProperties()
            ->setTitle("用户发文导出")
            ->setCreator("论坛后台管理")
            ->setDescription("table post");
        $workSheet = $spreadSheet->getSheet(0);
        $workSheet->setTitle("用户发文");

        $data = (Post::withTrashed())->alias("a")
            ->join("tp_user b", "a.user_id = b.id")
            ->join("tp_post c", "a.post_id = c.id", "left")
            ->join("tp_user d", "c.user_id = d.id", "left");
        if (input("get.start"))
            $data = $data->where("a.update_time", ">", strtotime(input("get.start")));
        if (input("get.end"))
            $data = $data->where("a.update_time", "<", strtotime(input("get.end")));

        $data = $data->field("a.id, a.text, a.create_time as create_time_raw, a.update_time as update_time_raw, 
            a.delete_time as delete_time_raw, a.media, a.user_id, a.post_id,
            b.name as user_name,
            c.update_time as reply_to_update_time_raw, c.user_id as reply_to_user_id, 
            d.name as reply_to_user_name")
            ->order(["update_time_raw" => "desc"])
            ->select();

        $fields = [
            "id" => ["id", "number"],
            "text" => ["内容", "string"],
            "create_time_raw" => ["发布时间", "datetime"],
            "update_time_raw" => ["修改时间", "datetime"],
            "delete_time_raw" => ["删除时间", "datetime"],
            "media" => ["视频路径", "string"],
            "user_id" => ["用户id", "number"],
            "user_name" => ["用户名", "string"],
            "post_id" => ["被回复发文id", "number"],
            "reply_to_update_time_raw" => ["被回复发文更新时间", "datetime"],
            "reply_to_user_id" => ["被回复发文用户id", "number"],
            "reply_to_user_name" => ["被回复发文用户名", "string"]
        ];
        $i = 0;
        foreach ($fields as $key => $value) {
            $workSheet->setCellValue(chr($i + 65) . 1, $value[0]);
            if ($value[1] == "datetime")
                $workSheet->getColumnDimension(chr($i + 65))->setWidth(20);
            else if ($key == "text" || $key == "media")
                $workSheet->getColumnDimension(chr($i + 65))->setWidth(40);
            $i++;
        }
        if (!empty($data)) {
            for ($i = 0; $i < count($data); $i++) {
                $j = 0;
                foreach (array_keys($fields) as $key) {
                    $cellName = chr($j + 65) . ($i + 2);
                    $cellData = $data[$i][$key];
                    if ($cellData && $fields[$key][1] == "datetime") {
                        $cellData = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($cellData);
                        $workSheet->getStyle($cellName)->getNumberFormat()
                            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DATETIME);
                    }
                    $workSheet->setCellValue($cellName, $cellData);
                    $j++;
                }
            }
        }
        $workSheet->getStyle("A1:" . chr(count($fields) + 65) . "1")->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB("FFFFFF00");
        $workSheet->getStyle($workSheet->calculateWorksheetDimension())->getFont()->setName("Microsoft YaHei");

        IOFactory::createWriter($spreadSheet, "Xlsx")->save("php://output");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="用户发文导出.xlsx"');
        header('Cache-Control: max-age=0');
        header('Expires: Mon, 1 Jan 1970 05:00:00 GMT');
    }

    public function import()
    {
        //生成导入用模板代码
        if ($this->request->isGet()) {
            $spreadSheet = new Spreadsheet();
            $spreadSheet->getProperties()
                ->setTitle("发文导入模板")
                ->setCreator("论坛后台管理");
            $workSheet = $spreadSheet->getSheet(0);
            $workSheet->setTitle("用户发文");

            //field里数组第二个为模板实例文字
            $fields = [
                "id" =>  ["number", 0],
                "内容" => ["string", "hello"],
                "发布时间" => ["datetime", \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(time())],
                "修改时间" => ["datetime", \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(time())],
                "删除时间" => ["datetime", null],
                "视频路径" => ["string", "/uploads/xxxxxx/xx.mp4"],
                "用户id" => ["number", 0],
                "被回复发文id" => ["number", 0],
            ];
            $i = 0;
            foreach ($fields as $key => $value) {
                $workSheet->setCellValue(chr($i + 65) . 1, $key);
                $workSheet->setCellValue(chr($i + 65) . 2, $value[1]);
                if ($value[0] == "datetime") {
                    $workSheet->getColumnDimension(chr($i + 65))->setWidth(20);
                    $workSheet->getStyle(chr($i + 65) . 2)->getNumberFormat()
                        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DATETIME);
                } else if ($key == "内容" || $key == "视频路径")
                    $workSheet->getColumnDimension(chr($i + 65))->setWidth(40);
                $i++;
            }

            $workSheet->getStyle("A1:" . chr(count($fields) + 65) . "1")->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB("FFFFFF00");
            $workSheet->getStyle($workSheet->calculateWorksheetDimension())->getFont()->setName("Microsoft YaHei");

            IOFactory::createWriter($spreadSheet, "Xlsx")->save("php://output");
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="发文导入模板.xlsx"');
            header('Cache-Control: max-age=0');
            header('Expires: Mon, 1 Jan 1970 05:00:00 GMT');
            return;
        }


        //处理上传代码
        //导入为直接导入数据库，且难以实现过滤文件路径等，分配导入权限必须谨慎
        if (empty(check_permission(session("aid"), "import_posts")))
            $this->error("权限不足。", "/management/admin_auth/login");
        if (!(new Validate(["__token__" => "require|token"]))->check(input("post.")))
            $this->error();

        $fname = ROOT_PATH . 'public' . DS . 'tmp' . DS . sanitize_filename(input("post.file"));
        try {
            $spreadSheet = IOFactory::load($fname);
        } catch (Exception $e) {
            $this->error();
        }
        $workSheet = $spreadSheet->getSheet(0);
        $highestRow = $workSheet->getHighestDataRow();
        $highestColumn = $workSheet->getHighestDataColumn();

        $fields = [ //用于定义excel表列和数据库表列的对应关系，导入的表中列不一定要一定顺序
            "id" => ["id", "number"],
            "内容" => ["text", "string"],
            "发布时间" => ["create_time", "datetime"],
            "修改时间" => ["update_time", "datetime"],
            "删除时间" => ["delete_time", "datetime"],
            "视频路径" => ["media", "string"],
            "用户id" => ["user_id", "number"],
            "被回复发文id" => ["post_id", "number"],
        ];
        $columnNumASCMapping = []; //excel表列的ASCII下标和数据库表列的对应关系
        for ($i = 65; $i <= ord($highestColumn); $i++) {
            if (!empty($fields[$workSheet->getCell(chr($i) . 1)->getValue()]))
                $columnNumASCMapping[$i] = $fields[$workSheet->getCell(chr($i) . 1)->getValue()];
        }

        $data = [];
        for ($i = 2; $i <= $highestRow; $i++) {
            $rowArr = [];
            //循环内会判定时间格式进行适当转换。
            for ($j = 65; $j <= ord($highestColumn); $j++) {
                if (empty($columnNumASCMapping[$j]))
                    continue;
                $fieldInfo = $columnNumASCMapping[$j];
                $cell = $workSheet->getCell(chr($j) . $i);
                $value = $cell->getValue();
                if ($fieldInfo[0] == "text" && !$value)
                    continue 2; //不插入空行
                if ($fieldInfo[1] == "datetime") {
                    if ($cell->getDataType() == "n")
                        $rowArr[$fieldInfo[0]] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($value);
                    else if ($cell->getDataType() == "s" && ($timeValue = strtotime($value)) !== false)
                        $rowArr[$fieldInfo[0]] = $timeValue;
                    else $rowArr[$fieldInfo[0]] = null;
                } else $rowArr[$fieldInfo[0]] = $value;
            }
            $data[] = $rowArr;
        }

        unlink($fname);
        //进行排序，使自增主键和发布时间对应，或在更新时避免冲突等
        usort($data, function ($a, $b) {
            if (($a["create_time"] ?? 0) == ($b["create_time"] ?? 0))
                return strcmp($a["text"], $b["text"]);
            else return (int)($a["create_time"] ?? 0) - (int)($b["create_time"] ?? 0);
        });
        if ((new Post())->saveAll($data))
            $this->success("导入成功。");
        else
            $this->error();
    }
}
