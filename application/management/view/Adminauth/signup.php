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
    </style>
    <script>
        function checkpwd(e) {
            if (document.forms["mainForm"].elements.upwd.value != document.forms["mainForm"].elements.upwdConfirm.value) {
                alert("密码不一致。")
                e.preventDefault()
            }
        }
    </script>
</head>

<body>
    <div id="mainSec">
        <h1>管理员注册</h1>
        <p>*注册后须联系超级管理员赋予管理角色。</p>
        <form id="mainForm" action="" method="post">
            <label for="uname">用户名</label>
            <input name="name" id="uname" type="text" placeholder="输入" required>
            <label for="uemail">邮箱地址</label>
            <input name="email" id="uemail" type="text" placeholder="输入" >
            <label for="upwd">密码</label>
            <input name="password" id="upwd" type="password" placeholder="输入" required>
            <label for="upwdConfirm">确认密码</label>
            <input id="upwdConfirm" type="password" placeholder="输入">
            <input type="hidden" name="__token__" value="{$Request.token}">
            <button type="submit" onclick="checkpwd(event)">注册</button>
        </form>
    </div>
</body>

</html>