{extend name="base" /}
{block name="inlineCss"}
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

    #infoTable {
        width: 100%;
    }

    #mainSecTitle {
        margin: 0px;
        font-size: 1.8em;
        padding-top: 1em;
        padding-bottom: 1em;
        font-weight: normal;
    }

    h3 {
        margin: 0px;
        font-size: 1.5em;
        padding-top: 1em;
        font-weight: normal;
        padding-bottom: 10px;
    }

    #textTitle {
        padding-top: 0px;
    }

    #contentSec {
        border: 1px solid rgb(215, 215, 215);
    }

    #videoSec {
        padding-top: 1em;
        padding-bottom: 1em;

        & video {
            width: 60%;
            border-radius: 4px;
        }
    }

    #rawSec {
        word-break: break-all;
    }

    #opSec {
        display: flex;
        padding-top: 1em;
        gap: 1em;
    }
</style>
{/block}
{block name="mainSec"}
<div id="mainSec">
    <h2 id="mainSecTitle">发文详细</h2>
    <h3 id="textTitle">内容</h3>
    <div id="contentSec">
        <div id="textSec"><?php echo $data["text"] ?></div>
        <?php if (!empty($data["media"])) { ?>
            <div id="videoSec">
                <video controls src="<?php echo "/uploads/" . $data['media']; ?>"></video>
            </div>
        <?php } ?>
    </div>
    <h3 id="rawTitle">原生</h3>
    <div id="rawSec"><?php echo htmlspecialchars($data["text"]) ?></div>
    <h3 id="infoTitle">信息</h3>
    <table id="infoTable">
        <tr>
            <th>id</th>
            <th>发布时间</th>
            <th>修改时间</th>
            <th>删除时间</th>
            <th>用户id</th>
            <th>用户名</th>
            <th>视频路径</th>
        </tr>
        <tr>
            <td><?php echo $data["id"]; ?></td>
            <td><?php echo $data["create_time"] ?></td>
            <td><?php echo $data["update_time"] ?></td>
            <td><?php echo $data["delete_time"] ?></td>
            <td><?php echo $data["user"]["id"] ?></td>
            <td><?php echo $data["user"]["name"] ?></td>
            <td><?php echo $data["media"] ?></td>
        </tr>
    </table>
    <h3 id="opTitle">操作</h3>
    <div id="opSec">
        <a href="/management/posts/delete?id=<?php echo $data["id"] ?>">
            <button <?php if (!check_permission(session("aid"), "delete_post")) echo "disabled" ?>>删除</button>
        </a>
        <a href="/management/posts/delete?id=<?php echo $data["id"] ?>&hard=true">
            <button <?php if (!check_permission(session("aid"), "delete_post_perm")) echo "disabled" ?>>永久删除</button>
        </a>
    </div>
</div>
{/block}