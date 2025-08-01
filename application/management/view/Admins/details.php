{extend name="base" /}
{block name="css"}
<style>
    #mainSec {
        margin: auto;
        margin-top: 80px;
        margin-bottom: 80px;
        min-width: max(650px, 75vw);
        max-width: 900px;
        padding-left: 50px;
        padding-right: 50px;
    }

    #mainSecTitle {
        margin: 0px;
        font-size: 1.8em;
        padding-top: 1em;
        padding-bottom: 1em;
        font-weight: normal;
    }

    #infoTitle {
        padding-top: 0px;
    }

    #infoTable {
        width: 100%;
    }

    h3 {
        margin: 0px;
        font-size: 1.5em;
        padding-top: 1.5em;
        font-weight: normal;
        padding-bottom: 0.4em;
    }

    #descriptSec {
        word-break: break-all;
        min-width: 400px;
        min-height: 140px;
    }

    #permTable {
        min-width: 400px;
    }

    #permEditSec {
        display: flex;
        align-items: center;
        gap: 45px;

        div {
            display: flex;
            align-items: center;
        }
    }

    #disabledEditSec {
        display: flex;
        align-items: center;
    }

    #opSec {
        display: flex;
        padding-top: 5em;
        gap: 1em;
        justify-content: center;
    }
</style>
{/block}
{block name="mainSec"}
<div id="mainSec">
    <h2 id="mainSecTitle">管理员 - <?php echo $data["name"] ?></h2>
    <h3 id="infoTitle">基本信息</h3>
    <table id="infoTable">
        <tr>
            <th>id</th>
            <th>用户名</th>
            <th>电子邮件</th>
            <th>注册时间</th>
        </tr>
        <tr>
            <td><?php echo $data["id"]; ?></td>
            <td><?php echo $data["name"] ?></td>
            <td><?php echo $data["email"] ?></td>
            <td><?php echo $data["create_time"] ?></td>
        </tr>
    </table>

    <h3 id="descriptTitle">描述</h3>
    <label for="descriptSec">编辑描述</label><br>
    <textarea name="description" id="descriptSec" form="mainForm"
        placeholder="无描述"><?php echo $data["description"] ?></textarea>

    <h3 id="permTitle">权限一览</h3>
    <table id="permTable">
        <tr>
            <th>角色id</th>
            <th>角色名称</th>
            <th>权限id</th>
            <th>权限名称</th>
        </tr>
        <?php foreach ($data->toArray()["role"] as $i) { ?>
            <tr>
                <td rowspan="<?php echo ($pCount = count($i["permission"])) ? $pCount : 1 ?>">
                    <?php echo $i["id"] ?>
                </td>
                <td rowspan="<?php echo $pCount ? $pCount : 1 ?>">
                    <?php echo $i["name"] ?>
                </td>
                <?php reset($i["permission"]) ?>
                <td><?php echo $pCount ? current($i["permission"])["id"] : "-" ?></td>
                <td><?php echo $pCount ? current($i["permission"])["name"] : "-" ?></td>
            </tr>
            <?php while ($j = next($i["permission"])) { ?>
                <tr>
                    <td><?php echo $j["id"]; ?></td>
                    <td><?php echo $j["name"]; ?></td>
                </tr>
            <?php } ?>
        <?php } ?>
    </table>

    <h3 id="permEditTitle">编辑角色</h3>
    <div id="permEditSec">
        <?php foreach ($allRoles as $i) {  ?>
            <div>
                <label for="roleCheck<?php echo $i["id"] ?>"><?php echo $i["name"] ?></label>
                <input id="roleCheck<?php echo $i["id"] ?>" type="checkbox" name="roles[]" value="<?php echo $i["id"] ?>" form="mainForm"
                    <?php
                    foreach ($data["role"] as $j)
                        if ($i["id"] == $j["id"]) {
                            echo "checked";
                            break;
                        }
                    if (!$permEditAdmin = check_permission(session("aid"), "edit_admin"))
                        echo " disabled";
                    ?>>
            </div>
        <?php } ?>
    </div>

    <h3 id="disabledEditTitle">禁用</h3>
    <div id="disabledEditSec">
        <label for="disabledEditSec-check">是否禁用</label>
        <input type="checkbox" name="disable" id="permEditSec-check" value="1" form="mainForm"
            <?php
            if ($data["disabled"])
                echo "checked";
            if (!check_permission(session("aid"), "edit_admin"))
                echo " disabled";
            ?>>
    </div>

    <form action="/management/admins/edit?id=<?php echo $data["id"] ?>" method="post" id="mainForm">
        <input type="hidden" name="__token__" value="{$Request.token}">
    </form>
    <div id="opSec">
        <input type="submit" form="mainForm" value="保存" <?php if (!$permEditAdmin) echo "disabled" ?>>
        <a href="/management/admins">
            <button <?php if (!$permEditAdmin) echo "disabled" ?>>放弃变更</button>
        </a>
    </div>
</div>
{/block}