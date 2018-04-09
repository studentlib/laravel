@extends("blades.index")
@section("script")
    <script src="{{asset("js/jquery-3.2.1.min.js")}}" type="text/javascript"></script>
    [if gte IE 8]
    <script src="{{asset("js/jquery-1.4.4.min.js")}}" type="text/javascript"></script>
    [endif]
    <script type="text/javascript">
        function isnull(cl) {
            alert("1231");
            alert(cl.text());
        }
        $(document).ready(function () {
            $("table").delegate("upd","click",function(){
                var idc=$(this).attr("class").split("_");
                var post={};
                post.userid=$("#usertd_"+idc[1]+"_userid");
                post.username=$("#usertd_"+idc[1]+"_username");
                post.password_original=$("#usertd_"+idc[1]+"_password_original");
                post.roleid=$("#usertd_"+idc[1]+"_roleid");
                post.email=$("#usertd_"+idc[1]+"_email");
                post.realname=$("#usertd_"+idc[1]+"_realname");
                post._token="{{csrf_token()}}";
                console.log(post);
                $.post("/setup/upd_user",post,function (data) {
                    alert(data);
                },"json");
            });
        });
    </script>
@endsection
@section("style")
    <link href="{{asset("css/admin/index.css")}}" rel="stylesheet" type="text/css">
    <style>
        ul, ol, li {
            float: left;
        }
        table tr td{
            text-align:left;
            font-size: 12px;
            vertical-align:middle;
        }
        .users_manage {
            color: #0553ad;
            font-size: 13px;
            margin: 20px 0 0 80px;

        }
        .users_table {
            margin-top: 50px;
            margin-left: 80px;

        }
        .users_table table {
            background-color: #4fe7ff;
            font-size: 16px;
        }
    </style>

@endsection
@section("backstage_content")
    <div class="backstage_content" >
        <ul class="users_manage">
            <li class="users" style="background-color: #37aaf9;word-wrap: break-word;"><a href="/setup/users_manage">管理员管理</a></li>
            <li>&nbsp;|&nbsp;</li>
            <li class="add_user"><a href="/setup/add_user_index">添加管理员</a></li>
        </ul>
        <div class="users_table">
            <table  contenteditable="true"  style="border-collapse:separate; border-spacing:40px 10px;">
                <tr contenteditable="false">
                    <td>顺序ID</td>
                    <td>用户名</td>
                    <td>所属角色</td>
                    <td>密码</td>
                    <td>权限ID</td>
                    <td>最后登录时间</td>
                    <td>最后登录IP</td>
                    <td>E-mail</td>
                    <td>真实姓名</td>
                    <td>管理操作</td>
                </tr>
                @for($i=0 ;$i<count($users);$i++)
                    <tr class="usertr_{{$i}}">
                        @foreach($users[$i] as $k=>$v)
                            <td class="usertd_{{$i}}_{{$k}}" onchange="isnull(this)">{{$v}}</td>
                        @endforeach
                            <td contenteditable="false">
                                <b style='cursor:pointer;color:blue;letter-spacing: 2px;' id="upd" class="upd_{{$i}}">修改</b>
                                &nbsp;&nbsp;|&nbsp;&nbsp;
                                <b style='cursor:pointer;color:blue;letter-spacing: 2px;' id="del" class="del_{{$i}}">删除</b>
                            </td>
                    </tr>
                @endfor
            </table>
        </div>
    </div>

@endsection