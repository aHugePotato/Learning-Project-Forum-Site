<!DOCTYPE html>
<html lang="cn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/static/style_management.css">
    <title>Document</title>
    <style>
        #mainSec {
            width: 350px;
            margin: auto;
            margin-top: 100px;
        }

        #mainFrom * {
            display: block;
        }

        #mainForm label {
            display: block;
            margin-top: 15px;
            width: 100%;
        }

        #mainForm input {
            width: 100%;
        }

        #mainForm button {
            margin-top: 15px;
            width: 100%;
        }

        #linkSec{
            padding-top: 3em;
            text-align: right;
        }
    </style>
</head>

<body>
    <div id="mainSec">
        <form id="mainForm" action="" method="post">
            <label for="uname">用户名</label>
            <input name="name" id="uname" type="text" placeholder="输入" required>
            <label for="upwd">密码</label>
            <input name="password" id="upwd" type="password" placeholder="输入" required>
            <input type="hidden" name="__token__" value="{$Request.token}">
            <button type="submit">登入</button>
        </form>
        <div id="linkSec">
            <a href="/management/adminauth/signup">注册</a>
        </div>
    </div>
</body>

</html>