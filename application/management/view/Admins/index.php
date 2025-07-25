{extend name="summary_base" /}
{block name="mainSecTitleText"}
管理员一览
{/block}
{block name="mainTable"}
<table id="mainTable">
    <tr>
        <th>id</th>
        <th>管理员名</th>
        <th>电子邮件</th>
        <th>描述</th>
        <th>注册时间</th>
        <th>角色</th>
        <th>禁用</th>
        <th>操作</th>
    </tr>
    <?php foreach ($tableData->toArray()["data"] as $i) { ?>
        <tr>
            <td><?php echo $i["id"]; ?></td>
            <td><?php echo $i["name"] ?></td>
            <td><?php echo $i["email"] ?></td>
            <td><?php echo $i["description"] ?></td>
            <td><?php echo $i["create_time"] ?></td>
            <td>
                <?php
                reset($i["role"]);
                echo current($i["role"])["name"];
                while ($j = next($i["role"])) echo " | " . $j["name"];
                ?>
            </td>
            <td><?php echo $i["disabled"] ? "是" : "否" ?></td>
            <td><a href="<?php echo "/management/admins/details/id/" . $i["id"] ?>">详细/管理</a></td>
        </tr>
    <?php } ?>
</table>
{/block}