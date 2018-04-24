@extends("blades.index")
@section("script")
    @parent
    <script type="text/javascript">
        $(document).ready(function () {
            $("table").delegate("#upd","click",function(){
                var idc=$(this).attr("class").split("_");
                var post={};
                post.userid=$(".usertd_"+idc[1]+"_userid").text();
                post.username=$(".usertd_"+idc[1]+"_username").text();
                post.password_original=$(".usertd_"+idc[1]+"_password_original").text();
                post.roleid=$(".usertd_"+idc[1]+"_roleid").text();
                post.email=$(".usertd_"+idc[1]+"_email").text();
                post.realname=$(".usertd_"+idc[1]+"_realname").text();
                post._token="{{csrf_token()}}";
                for (var v in post){
                    if(post[v]==""){
                        alert("修改参数不能为空");
                        return;
                    };
                }
                $.post("/setup/upd_user",post,function (data) {
                    alert(data.msg);
                    location.reload();
                },"json");
            });

            $("table").delegate("#del","click",function(){
                var idc=$(this).attr("class").split("_");
                var post={};
                post.userid=$(".usertd_"+idc[1]+"_userid").text();
                post._token="{{csrf_token()}}";
                $.post("/setup/del_user",post,function (data) {
                    alert(data.msg);
                    location.reload();
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
    <div class="content" >
        <ul class="users_manage">
            <li class="users" style="background-color: #37aaf9;word-wrap: break-word;"><a href="/setup/users_manage">管理员管理</a></li>
            <li>&nbsp;|&nbsp;</li>
            <li class="add_user"><a href="/setup/add_user_index">添加管理员</a></li>
        </ul>
        <div class="users_table">
            <table style="border-collapse:separate; border-spacing:40px 10px;">
                <tr>
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
                            <?php
                                if($k=="userid"){
                                    $contenteditable="contenteditable=false";
                                }else{
                                    $contenteditable="contenteditable=true";
                                }
                            ?>
                            <td class="usertd_{{$i}}_{{$k}}" {{$contenteditable}}>{{$v}}</td>
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