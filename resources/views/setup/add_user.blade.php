@extends("blades.index")
@section("script")
    <script src="{{asset("js/jquery-3.2.1.min.js")}}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $(".save").click(function(){
                var post={};
                post.username=$(".username").val();
                post.password=$(".password").val();
                post.email=$(".email").val();
                if(post.email.indexOf("@") < 0 ){
                    alert('最好是email');
                }
                post.realname=$(".realname").val();
                post.roleid=$(".role").val();
                post.rolename=$(".role").find("option:selected").text();
                post._token="{{csrf_token()}}";
                $.post("/setup/add_user",post,function (data) {
                    alert(data);
                },"json");
            });
        });
    </script>
@endsection
@section("style")
    <link href="{{asset("css/admin/index.css")}}" rel="stylesheet" type="text/css">
    <style type="text/css">
        ul, ol, li {
            float: left;
        }
        table tr td{
            text-align:left;
            vertical-align:middle;
        }
        .users_manage {
            color: #444;
            font-size: 13px;
            margin: 20px 0 0 80px;
        }
        .users_table {
            margin-top: 50px;
            margin-left: 80px;
        }
    </style>

@endsection
@section("backstage_content")
    <div class="backstage_content" >
        <ul class="users_manage">
            <li class="users" ><a href="/setup/users_manage">管理员管理</a></li>
            <li>&nbsp;|&nbsp;</li>
            <li class="add_user" style="background-color: #37aaf9;word-wrap: break-word;"><a href="/setup/add_user_index">添加管理员</a></li>
        </ul>
        <div class="users_table">
            <table align="left" style="border-collapse:separate; border-spacing:10px 20px;">
                <tr>
                    <td>用户名：</td><td><input type="text" class="username" id="username" style="width: 250px"/></td><td>
                        <span>&nbsp;&nbsp;&nbsp;&nbsp;请输入用户名</span><span style="color: red">(长度大于四)</span></td>
                </tr>
                <tr>
                    <td>密码：</td><td><input type="text" class="password" id="password" style="width: 250px"/></td><td>
                        <span>&nbsp;&nbsp;&nbsp;&nbsp;请输入密码</span><span style="color: red">(6位及以上)</span></td>
                </tr>
                <tr>
                    <td>E-mail：</td><td><input type="text" class="email" id="email" style="width: 250px"/></td><td><span>&nbsp;&nbsp;&nbsp;&nbsp;请输入E-mail</span></td>
                </tr>
                <tr>
                    <td>真实姓名：</td><td><input type="text" class="realname" id="realname" style="width: 250px"/></td><td><span>&nbsp;&nbsp;&nbsp;&nbsp;请输入姓名</span></td>
                </tr>
                <tr>
                    <td>管理员分类：</td>
                    <td>
                        <select class="role"  id="role" style="width: 140px;height: 30px;font-size: 12px">
                            @foreach($roles as $k=>$v)
                                <option value="{{$v["roleid"]}}">{{$v["rolename"]}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><span>&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                </tr>
                <tr>
                    <td><input type="button" class="save" id="save" value="注册"  style="width: 80px"/></td>
                </tr>
            </table>
        </div>
    </div>

@endsection