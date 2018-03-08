<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset("css/signin.css")}}" rel="stylesheet" type="text/css">
    <title>登录界面</title>
    <style>
        .content{
            background-image: url("{{asset('/images/login_bg.jpg')}}");
        }
    </style>
</head>
<body>
<div class="content">

    <div class="login_iptbox">
        <form action="/login/checkLogin" method="post" name="myform" height="30px">
            {{ csrf_field() }}
        <label>用户：</label><input name="username" class="login_ipt" type="text" align="center" placeholder="用户">
        <label>密码：</label><input name="password" class="login_ipt" type="password" placeholder="密码">
        <input type="submit" value="登录" >
        </form>
        <div class="form-sign"><h2>{{session("message")}}</h2></div>
    </div>
</div>

</body>
</html>