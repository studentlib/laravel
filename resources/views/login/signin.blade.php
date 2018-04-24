<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang("login.title")</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset("css/signin.css")}}" rel="stylesheet" type="text/css">
    <script src="{{asset("js/jquery-3.2.1.min.js")}}" type="text/javascript"></script>

    <style>
        .content{
            background-image: url("{{asset('/images/login_bg.jpg')}}");
        }
    </style>
</head>
<body onload="javascript:document.myform.username.focus();">
<div class="content">
    <div class="login_iptbox">
        <form action="/login/checkLogin" method="post" name="myform" height="30px">
            {{ csrf_field() }}
            <label>@lang("login.user")：</label><input name="username" id="username" type="text" align="center" placeholder="@lang("login.user")">
            <label>@lang("login.password")：</label><input name="password" type="password" placeholder="@lang("login.password")">
            <input type="submit" value="@lang("login.login")" >
        </form>
        <div class="form-sign"><h2>{{session("error_message")}}</h2></div>
    </div>
</div>

</body>
</html>