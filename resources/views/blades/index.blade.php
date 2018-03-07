@section("head")
<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<meta name="viewport"
      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>gm首页</title>
<link href="{{asset("css/admin/index.css")}}" rel="stylesheet" type="text/css">
<script href="{{asset("js/jquery-3.2.1.min.js")}}"></script>
<style>

</style>
<body>
@show
//后台首页顶部
@section("backstage_top")
<div class="backstage_top">
    <div class="logo_lf" id="logo_lf">
        <a href="/login"><img src="{{asset('images/logo.gif')}}"></a>
    </div>
    <div class="top_narration" >
        <span>您好 ！{{session("username")}} &nbsp;|</span>
        <span>&nbsp;[ {{session("rolename")}} ]&nbsp;|</span>
        <span>&nbsp;[<a href="/logout" >退出</a>]&nbsp;|</span>
         @if(session("roleid")==1)
             <span>&nbsp;[<a href='' >管理员中心</a>]&nbsp;|</span>
         @endif
    </div>
    <div class="top_menu">
        <ul>
            @foreach(Session::all()['top_menu'] as $k=>$menu)
                    <b><a href="{{$menu['redirect_url']}}"><li>{{$menu['menu_name'] or $menu['menu_li']}}</li></a></b>
            @endforeach
        </ul>
    </div>
</div>
@show
//这是后台首页左侧导航栏
@section("backstage_left")
<div class="backstage_left">
    <div class="left_main"></div>
    <a class="left_botton" href=""></a>
</div>
@show
//这是后台首页内容主体部分
@section("backstage_content")
<div class="backstage_content">
    <h2>这是后台首页内容主体部分</h2>
</div>
@show
@section("foot")
</body>
</html>
@show