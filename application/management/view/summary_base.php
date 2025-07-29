<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/static/style_management.css">
    <title>Posts</title>

    <style>
        #navMidSec {
            display: flex;
            list-style-type: none;
            gap: 20px;

            li {
                height: 50px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.25em;

                &.currentPage {
                    border-bottom: 2px solid #1eb8ff;
                }

                & * {
                    color: black;
                }
            }
        }
    </style>
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

        #mainTable {
            width: 100%;
        }

        #mainSecTitle,
        #mainSecTitle2 {
            margin: 0px;
            font-size: 1.8em;
            padding-top: 1em;
            padding-bottom: 1em;
            font-weight: normal;
        }

        #sumTable td,
        #sumTable th {
            padding-left: 1em;
            padding-right: 1em;
        }
    </style>
    {/block}
</head>

<body>
    <div id="nav">
        <h1 id="title">综合管理</h1>
        <ul id="navMidSec">
            <li {$Request.controller=="Posts" ?='class="currentPage"' }><a href="/management/posts"><span>发文</span></a></li>
            <li {$Request.controller=="Users" ?='class="currentPage"' }><a href="/management/users"><span>用户(待制作)</span></a></li>
            <li {$Request.controller=="Admins" ?='class="currentPage"' }><a href="/management/admins"><span>管理员</span></a></li>
        </ul>
        <div id="uSec">
            <div><?php echo $ainfo["name"]; ?></div>
            <a href="/management/admin_auth/logout"> 登出</a>
        </div>
    </div>
    <div id="mainSec">
        <h2 id="mainSecTitle">{block name="mainSecTitleText"}{/block}</h2>
        {block name="mainTable"}
        {/block}

        {block name="paginate"}
        <?php echo $tableData->render() ?>
        {/block}
        <h2 id="mainSecTitle2">统计</h2>
        {block name="sumTable"}
        <table id="sumTable">
            <tr>
                <th>合计</th>
            </tr>
            <tr>
                <td><?php echo $tableSum[0]["count"] ?></td>
            </tr>
        </table>
        {/block}
    </div>
    <div id="bottomSec">
    </div>
</body>

</html>