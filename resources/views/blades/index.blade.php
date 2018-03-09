@section("head")
<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<meta name="viewport"
      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>gm首页</title>
<link href="{{asset("css/admin/index.css")}}" rel="stylesheet" type="text/css">
<script src="{{asset("js/jquery-3.2.1.min.js")}}" type="text/javascript"></script>
[if gte IE 8]
<script src="{{asset("js/jquery-1.4.4.min.js")}}" type="text/javascript"></script>
[endif]
<script type="text/javascript">
    $(document).ready(function () {
        $("#openclose").click(function(){
            $(".left_main").hide();
        });
    });
</script>
<style></style>
<body>
@show
{{--//后台首页顶部--}}
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
             <span>&nbsp;[<a href='../../setup/setup/设置' >管理员中心</a>]&nbsp;|</span>
         @endif
    </div>
    <div class="top_menu">
        <ul>
            @foreach(Session::all()['top_menu'] as $k=>$menu)
                    <b><a href="{{$menu['redirect_url']."/".$menu['menu_li']."/".$menu['menu_name']}}"><li>{{$menu['menu_name'] or $menu['menu_li']}}</li></a></b>
            @endforeach
        </ul>
    </div>
</div>
@show
{{--//这是后台首页左侧导航栏--}}
@section("backstage_left")
<div class="backstage_left">
    <div class="left_main" id="left_main"></div>
    <a href="javascript:;" id="openclose" class="openclose"></a>
</div>
@show
{{--//这是后台首页内容主体部分--}}
@section("backstage_content")
<div class="backstage_content">
    <h2>这是后台首页内容主体部分</h2>
</div>
@show
@section("foot")
</body>
</html>
@show