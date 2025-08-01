{extend name="summary_base" /}
{block name="css"}
<link rel="stylesheet" href="/static/filepond-master/dist/filepond.css">
<style>
    #mainSec {
        margin: auto;
        margin-top: 80px;
        margin-bottom: 80px;
        min-width: max(650px, 85vw);
        max-width: 900px;
        padding-left: 50px;
        padding-right: 50px;
    }

    #mainTable {
        width: 100%;
    }

    h2 {
        margin: 0px;
        font-size: 1.8em;
        padding-top: 1em;
        padding-bottom: 0.4em;
        font-weight: normal;
    }

    p {
        padding: 0px;
        margin-top: 0px;
        margin-bottom: 1em;
    }

    #sumTable td,
    #sumTable th {
        padding-left: 1em;
        padding-right: 1em;
    }

    #exportForm-datetime {
        display: flex;
        gap: 20px;

        div {
            display: flex;
            align-items: center;
            gap: 5px;
        }
    }

    #exportForm-submit,
    #importForm-submit {
        margin-top: 20px;
    }
</style>
{/block}
{block name="scripts"}
<script src="/static/filepond-master/dist/filepond.js" referrerpolicy="origin"></script>
<script src="/static/filepond-plugin-file-validate-type-master/dist/filepond-plugin-file-validate-type.js" referrerpolicy="origin"></script>
<script type="module">
    import zh_CN from '/static/filepond-master/locale/zh-cn.js';

    function onDOMCLoad() {
        FilePond.setOptions(zh_CN);
        FilePond.registerPlugin(FilePondPluginFileValidateType);
        const inputElement = document.querySelector('.filepond')
        const pond = FilePond.create(inputElement, {
            server: "/management/upload_handler",
            acceptedFileTypes: ["application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"],
        })
    }
    document.addEventListener("DOMContentLoaded", onDOMCLoad)
</script>
{/block}
{block name="mainSecTitleText"}
发文一览
{/block}
{block name="mainTable"}
<table id="mainTable">
    <tr>
        <th>id</th>
        <th>内容缩略</th>
        <th>发布时间</th>
        <th>修改时间</th>
        <th>删除时间</th>
        <th>用户id</th>
        <th>用户名</th>
        <th>视频路径</th>
        <th>的回复</th>
        <th>操作</th>
    </tr>
    <?php foreach ($tableData as $i) { ?>
        <tr>
            <td><?php echo $i["id"]; ?></td>
            <td><?php echo strip_tags($i["text"]) ?></td>
            <td><?php echo $i["create_time"] ?></td>
            <td><?php echo $i["update_time"] ?></td>
            <td><?php echo $i["delete_time"] ?></td>
            <td><?php echo $i["user_id"] ?></td>
            <td><?php echo $i["user_name"] ?></td>
            <td><?php echo $i["media"] ?></td>
            <td><?php echo $i["post_id"] ?></td>
            <td><a href="<?php echo "/management/posts/details/id/" . $i["id"] ?>">详细/管理</a></td>
        </tr>
    <?php } ?>
</table>
{/block}
{block name="others"}
<h2 id="mainSecTitle2">统计</h2>
<table id="sumTable">
    <tr>
        <th>合计</th>
    </tr>
    <tr>
        <td><?php echo $tableSum[0]["count"] ?></td>
    </tr>
</table>
<h2 id="mainSecTitle3">导出数据</h2>
<form action="/management/posts/export" id="exportForm" method="get">
    <div id="exportForm-datetime">
        <div>
            <label for="exportSec-startTime">开始时间</label>
            <input type="datetime-local" name="start" id="exportSec-startTime">
        </div>
        <div>
            <label for="exportSec-endTime">截止时间</label>
            <input type="datetime-local" name="end" id="exportSec-endtTime">
        </div>
    </div>
    <div id="exportForm-submit">
        <input type="submit" value="导出">
    </div>
</form>
<?php if (check_permission(session("aid"), "import_posts")) { ?>
    <h2 id="mainSecTitle4">导入</h2>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;从excel表格直接导入到数据库。请谨慎操作。新增时发文id必须留空,除新增之外还可以进行修改,修改时使用对应id。
        请参照<a href="/management/posts/import">模板</a>格式进行excel编辑。时间数据类型建议为:更多格式->自定义中的m/d/yyyy h:mm,其他数据用“常规”即可。
        导出时生成的excel的格式可用于导入。但关联表的内容将被忽略。由于自增主键的关系,若要导入一条发文和其回复,请先在一个文件中导入发文,
        再参照其id,在另一文件中导入回复</p>
    <form action="/management/posts/import" method="post" id="importForm">
        <input type="hidden" name="__token__" value="{$Request.token}">
        <label for="importForm-fileSelBut">上传excel表格:</label><br>
        <input name="file" type="file" class="filepond" id="importForm-fileSelBut">
        <div id="importForm-submit">
            <input type="submit" value="提交">
        </div>
    </form>
<?php } ?>
{/block}