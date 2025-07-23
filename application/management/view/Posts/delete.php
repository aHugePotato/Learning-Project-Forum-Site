<!DOCTYPE html>
<html lang="cn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/static/style.css">
    <title>confirm</title>
    <style>
        #mainSec {
            margin: 150px;
        }

        #msg {
            font-size: 2em;
        }

        #actionSec {
            padding-top: 2em;
            display: flex;
            justify-content: flex-start;
            gap: 10px;
        }
    </style>
</head>

<body>
    <div id="mainSec">
        <p id="msg">确认<?php if (!empty($hard)) echo "永久"; ?>删除?</p>
        <div id="actionSec">
            <form action="" method="post">
                <button type="submit">是</button>
                <input type="hidden" name="__token__" value="{$Request.token}">
            </form>
            <a href="/management/posts"><button>否</button></a>
        </div>
    </div>
</body>

</html>