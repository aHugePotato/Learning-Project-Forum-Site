{extend name="summary_base" /}
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
        <th>操作</th>
    </tr>
    <?php foreach ($tableData as $i) { ?>
        <tr>
            <td><?php echo $i["id"]; ?></td>
            <td><?php echo strip_tags($i["text"]) ?></td>
            <td><?php echo $i["create_time"] ?></td>
            <td><?php echo $i["update_time"] ?></td>
            <td><?php echo $i["delete_time"] ?></td>
            <td><?php echo $i["user"]["id"] ?></td>
            <td><?php echo $i["user"]["name"] ?></td>
            <td><?php echo $i["media"] ?></td>
            <td><a href="<?php echo "/management/posts/details/id/" . $i["id"] ?>">详细/管理</a></td>
        </tr>
    <?php } ?>
</table>
{/block}