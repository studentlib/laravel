@extends("blades.index")
@section("script")
    @parent
    <script type="text/javascript">
        function scroll(viewid,scrollid,size){
            // 获取滚动条容器
            var container = document.getElementById(scrollid);
            // 将表格拷贝一份
            var tb2 = document.getElementById(viewid).cloneNode(true);
            // 获取表格的行数
            var len = tb2.rows.length;
            // 将拷贝得到的表格中非表头行删除
            for(var i=tb2.rows.length;i>size;i--){
                // 每次删除数据行的第一行
                tb2.deleteRow(size);
            }
            // 创建一个div
            var bak = document.createElement("div");
            // 将div添加到滚动条容器中
            container.appendChild(bak);
            // 将拷贝得到的表格在删除数据行后添加到创建的div中
            bak.appendChild(tb2);
            // 设置创建的div的position属性为absolute，即绝对定于滚动条容器（滚动条容器的position属性必须为relative）
            bak.style.position = "absolute";
            // 设置创建的div的背景色与原表头的背景色相同（貌似不是必须）
            bak.style.backgroundColor = "white";
            // 设置div的display属性为block，即显示div（貌似也不是必须，但如果你不希望总是显示拷贝得来的表头，这个属性还是有用处的）
            bak.style.display = "block";
            // 设置创建的div的left属性为0，即该div与滚动条容器紧贴
            bak.style.left = 0;
            // 设置div的top属性为0，初期时滚动条位置为0，此属性与left属性协作达到遮盖原表头
            bak.style.top = "0px";
            bak.style.margin="3.2px 0 0 9px";
            bak.style.width = "100%";
            // 给滚动条容器绑定滚动条滚动事件，在滚动条滚动事件发生时，调整拷贝得来的表头的top值，保持其在可视范围内，且在滚动条容器的顶端
            container.onscroll = function(){
                // 设置div的top值为滚动条距离滚动条容器顶部的距离值
                bak.style.top = this.scrollTop+"px";
            }
        }

        // 在页面加载完成后调用该方法
        // window.onload = function (){
        //     scroll("heros","box",2);
        // }

        $(document).ready(function(){
            $("#userid").mouseup(function(){
                var th=this;
                //查询基本信息
                $.getJSON("/gm/getInfo/"+$(th).val(),function(data){
                    if(data&&data.uid){
                        $.each(data,function(k,v){
                            $('#'+k).val(v);
                        });
                    }else{
                        $("#edit input[class='info']").each(function(){
                            $(this).val("");
                        });
                        alert("玩家信息没找到");
                    }
                },'json');
                //查询英雄
                $.getJSON("/gm/getHeros/"+$(th).val(),function(hdata){
                    $("#heros tr").eq(2).nextAll().remove();
                    if(hdata)
                    {
                        var str='';
                        $.each(hdata,function(k,v){
                            str+="<tr>";
                            $.each(v,function(m,n){
                                var cont="";
                                //判断是否可修改
                                if(m=="level"){cont="contenteditable='true' style='background-color:white;font-size:12px;'"}
                                str+="<th "+cont+" style='font-size:12px;' class='"+m+"_"+v.huid+"'>"+n+"</th>";
                            });
                            str+="<th><b style='cursor:pointer;color:blue;' id='hupd' class='hupd_"+v.huid+"'>修改</b></th><th style='font-size:12px;'>"+v.huid+"</th></tr>";
                        });
                        $("#heros").append(str);
                    }else{
                        alert("英雄不存在");
                    }
                },'json');
                //查询资源包
                $.getJSON("/gm/getMoney/"+$(th).val(),function(data){
                    $("#money input[id='addCount']").each(function(){
                        $(this).val("");
                    });
                    if(data&&data.money){
                        $.each(data.money,function(kk,vv){
                            var mcount="#count_"+vv.id;
                            $(mcount).val(vv.count);
                        });
                    }else{
                        alert("玩家不存在");
                    }
                },'json');

                //查询主城
                $.getJSON("/gm/getCitys/"+$(th).val(),function(cdata){
                    $("#citys").empty();
                    if (cdata==undefined) {return;}
                    var citys="";
                    $.each(cdata,function(k,v){
                        // $.each(v,function(m,n){
                        //<input type="button"  id="city_upd" name="city_'+v.id+'"  value="修改等级">
                        //citys+='<label>'+m+':</label><input type="text" name="'+m+'_'+v.id+'" id="'+m+'_'+v.id+'" value="'+n+'"/>';
                        // });
                        citys+='<label>建筑ID:</label><input type="text" name="id_'+v.id+'" id="id_'+v.id+'" value="'+v.id+'"/>';
                        citys+='<label>建筑等级:</label><input type="text" name="level_'+v.id+'" id="level_'+v.id+'" value="'+v.level+'"/>';
                        citys+='<label>上次升级时间:</label><input type="text" name="upgradetime_'+v.id+'" id="upgradetime_'+v.id+'" value="'+v.upgradetime+'"/>';
                        citys+="<br/><br/>";
                    });
                    $("#citys").append(citys);
                },'json');

                //查询技能信息
                $.getJSON("/gm/getSkills/"+$(th).val(),function(sdata){
                    $("#skills").empty();
                    if (sdata==undefined) {return;}
                    var skills="";
                    $.each(sdata,function(k,v){
                        // $.each(v,function(m,n){
                        //<input type="button"  id="city_upd" name="city_'+v.id+'"  value="修改等级">
                        //skills+='<label>'+m+':</label><input type="text"  readonly="readonly" name="'+m+'_'+v.id+'" id="'+m+'_'+v.id+'" value="'+n+'"/>';
                        // });
                        skills+='<label>技能id:</label><input type="text"  readonly="readonly" name="group_'+k+'" id="group_'+k+'" value="'+v.group+'"/>';
                        skills+='<label>学习程度:</label><input type="text"  readonly="readonly" name="study_'+k+'" id="study_'+k+'" value="'+v.study+'"/>';
                        skills+='<label>使用次数:</label><input type="text"  readonly="readonly" name="used_'+k+'" id="used_'+k+'" value="'+v.used+'"/>';
                        skills+="<br/><br/>";
                    });
                    $("#skills").append(skills);
                },'json');

                //查询队伍信息
                $.getJSON("/gm/getTeams/"+$(th).val(),function(tdata){
                    //eq(1).nextAll().remove();
                    $("#teams tr").eq(2).nextAll().remove();
                    if (tdata.code) {return;}//alert(tdata.msg);
                    var teams="";
                    $.each(tdata,function(ktr,vtr){
                        teams+="<tr>";
                        if (vtr.teamid!=null) {
                            $.each(vtr,function(kth,vth){
                                if ( typeof(vth) == 'object'&&vth!=null){
                                    $.each(vth,function(kth_th,vth_th){
                                        teams+="<th style='font-size:12px;'>"+vth_th+"</th>";
                                    });
                                }else{
                                    teams+="<th style='font-size:12px;'>"+vth+"</th>";
                                }
                            });
                            teams+="</tr>";
                        }
                    });
                    $("#teams").append(teams);
                },'json');
            });
            $('#search').click(function(){
                $('#error').html("");
                var th="#suid";
                //查询基本信息
                $.getJSON("/gm/getInfo/"+$(th).val(),function(data){
                    if(data&&data.uid){
                        $.each(data,function(k,v){
                            $('#'+k).val(v);
                        });
                    }else{
                        $("#edit input[class='info']").each(function(){
                            $(this).val("");
                        });
                        alert("玩家信息没找到");
                    }
                },'json');
                //查询英雄
                $.getJSON("/gm/getHeros/"+$(th).val(),function(hdata){
                    $("#heros tr").eq(2).nextAll().remove();
                    if(hdata)
                    {
                        var str='';
                        $.each(hdata,function(k,v){
                            str+="<tr>";
                            $.each(v,function(m,n){
                                var cont="";
                                //判断是否可修改
                                if(m=="level"){cont="contenteditable='true' style='background-color:white;font-size:12px;'"}
                                str+="<th "+cont+" style='font-size:12px;' class='"+m+"_"+v.huid+"'>"+n+"</th>";
                            });
                            str+="<th><b style='cursor:pointer;color:blue;' id='hupd' class='hupd_"+v.huid+"'>修改</b></th><th style='font-size:12px;'>"+v.huid+"</th></tr>";
                        });
                        $("#heros").append(str);
                    }else{
                        alert("英雄不存在");
                    }
                },'json');
                window.onload = function (){
                    scroll("heros","box",2);
                }
                //查询资源包
                $.getJSON("/gm/getMoney/"+$(th).val(),function(data){
                    $("#money input[id='addCount']").each(function(){
                        $(this).val("");
                    });
                    if(data&&data.money){
                        $.each(data.money,function(kk,vv){
                            var mcount="#count_"+vv.id;
                            $(mcount).val(vv.count);
                        });
                    }else{
                        alert("玩家不存在");
                    }
                },'json');

                //查询主城
                $.getJSON("/gm/getCitys/"+$(th).val(),function(cdata){
                    $("#citys").empty();
                    if (cdata==undefined) {return;}
                    var citys="";
                    $.each(cdata,function(k,v){
                        // $.each(v,function(m,n){
                        //<input type="button"  id="city_upd" name="city_'+v.id+'"  value="修改等级">
                        //citys+='<label>'+m+':</label><input type="text" name="'+m+'_'+v.id+'" id="'+m+'_'+v.id+'" value="'+n+'"/>';
                        // });
                        citys+='<label>建筑ID:</label><input type="text" name="id_'+v.id+'" id="id_'+v.id+'" value="'+v.id+'"/>';
                        citys+='<label>建筑等级:</label><input type="text" name="level_'+v.id+'" id="level_'+v.id+'" value="'+v.level+'"/>';
                        citys+='<label>上次升级时间:</label><input type="text" name="upgradetime_'+v.id+'" id="upgradetime_'+v.id+'" value="'+v.upgradetime+'"/>';
                        citys+="<br/><br/>";
                    });
                    $("#citys").append(citys);
                },'json');

                //查询技能信息
                $.getJSON("/gm/getSkills/"+$(th).val(),function(sdata){
                    $("#skills").empty();
                    if (sdata==undefined) {return;}
                    var skills="";
                    $.each(sdata,function(k,v){
                        // $.each(v,function(m,n){
                        //<input type="button"  id="city_upd" name="city_'+v.id+'"  value="修改等级">
                        //skills+='<label>'+m+':</label><input type="text"  readonly="readonly" name="'+m+'_'+v.id+'" id="'+m+'_'+v.id+'" value="'+n+'"/>';
                        // });
                        skills+='<label>技能id:</label><input type="text"  readonly="readonly" name="group_'+k+'" id="group_'+k+'" value="'+v.group+'"/>';
                        skills+='<label>学习程度:</label><input type="text"  readonly="readonly" name="study_'+k+'" id="study_'+k+'" value="'+v.study+'"/>';
                        skills+='<label>使用次数:</label><input type="text"  readonly="readonly" name="used_'+k+'" id="used_'+k+'" value="'+v.used+'"/>';
                        skills+="<br/><br/>";
                    });
                    $("#skills").append(skills);
                },'json');

                //查询队伍信息
                $.getJSON("/gm/getTeams/"+$(th).val(),function(tdata){
                    //eq(1).nextAll().remove();
                    $("#teams tr").eq(2).nextAll().remove();
                    if (tdata.code) {return;}//alert(tdata.msg);
                    var teams="";
                    $.each(tdata,function(ktr,vtr){
                        teams+="<tr>";
                        if (vtr.teamid!=null) {
                            $.each(vtr,function(kth,vth){
                                if ( typeof(vth) == 'object'&&vth!=null){
                                    $.each(vth,function(kth_th,vth_th){
                                        teams+="<th style='font-size:12px;'>"+vth_th+"</th>";
                                    });
                                }else{
                                    teams+="<th style='font-size:12px;'>"+vth+"</th>";
                                }
                            });
                            teams+="</tr>";
                        }
                    });
                    $("#teams").append(teams);
                },'json');
            });
            //添加英雄
            $('table').delegate("#hadd","click",function(){
                $.getJSON("/gm/addHero?uid="+$("#uid").val()+"&hid="+$("#heroid_0").text()+"&sid="+$("#servers").val(),function(data){
                    if(data.code==0)
                    {
                        alert($("#heroid_0").text()+"英雄"+data.msg);
                        $("#heroid_0").text("");
                        $.getJSON("/gm/getHeros/"+$("#uid").val(),function(hdata){
                            $("#heros tr").eq(2).nextAll().remove();
                            if(hdata)
                            {
                                var str='';
                                $.each(hdata,function(k,v){
                                    str+="<tr>";
                                    $.each(v,function(m,n){
                                        var cont="";
                                        //判断是否可修改
                                        if(m=="level"){cont="contenteditable='true' style='background-color:white;'"}
                                        str+="<th "+cont+" style='font-size:12px' class='"+m+"_"+v.huid+"'>"+n+"</th>";
                                    });
                                    str+="<th><b style='cursor:pointer;color:blue;' id='hupd' class='hupd_"+v.huid+"'>修改</b></th><th style='font-size:12px;'>"+v.huid+"</th></tr>";
                                });
                                $("#heros").append(str);
                            }else{
                                alert("英雄不存在");
                            }
                        },'json');
                    }else{
                        alert($("#huid_0").text()+"英雄添加失败");
                    }
                },"json");
            });
            //修改英雄等级
            $('table').delegate("#hupd","click",function(){
                var idh=$(this).attr("class").split("_");
                var post={};
                post.uid=$("#uid").val();
                post.sid=$("#servers").val();
                var hero={};
                hero.hid=$('.heroid_'+idh[1]).text();
                hero.level=$('.level_'+idh[1]).text();
                post.hero=hero;
                post._token="{{ csrf_token() }}";
                $.post("/gm/updHero",post,function(data){
                    alert(data.msg);
                    $.getJSON("/gm/getHeros/"+$("#uid").val(),function(hdata){
                        $("#heros tr").eq(2).nextAll().remove();
                        if(hdata)
                        {
                            var str='';
                            $.each(hdata,function(k,v){
                                str+="<tr>";
                                $.each(v,function(m,n){
                                    var cont="";
                                    //判断是否可修改
                                    if(m=="level"){cont="contenteditable='true' style='background-color:white;'"}
                                    str+="<th "+cont+" style='font-size:12px' class='"+m+"_"+v.huid+"'>"+n+"</th>";
                                });
                                str+="<th><b style='cursor:pointer;color:blue;' id='hupd' class='hupd_"+v.huid+"'>修改</b></th><th style='font-size:12px;'>"+v.huid+"</th></tr></tr>";
                            });
                            $("#heros").append(str);
                        }else{
                            alert("英雄不存在");
                        }
                    },'json');
                },'json');
            });
            //添加资源数量
            $('#addAllMoney').click(function(){
                var money={};
                var post={};
                post.uid=$("#uid").val();
                post.sid=$("#servers").val();
                $("#money input[id='addCount']").each(function(){
                    var moneyid=$(this).attr("name");
                    var addMoney=$(this).val();
                    if(addMoney!=0&&addMoney!=undefined){
                        money[moneyid]=addMoney;
                    }
                    $(this).val("");
                });
                post.money=money;
                post._token="{{ csrf_token() }}";
                $.post("/gm/addMoney",post,function(data){
                    alert(data.msg);
                    $.getJSON("/gm/getMoney/"+$("#uid").val(),function(data) {
                        if (data && data.money) {
                            $.each(data.money, function (kk, vv) {
                                var mcount = "#count_" + vv.id;
                                $(mcount).val(vv.count);
                            });
                        } else {
                            alert("玩家信息没找到");
                        }
                    },'json');
                },'json');
            });
            //添加资源
            $('#add_item').click(function(){
                if($('#uid').val()!='')
                {
                    $.getJSON("/gm/addItem/"+$('#uid').val()+"&tid="+$('#item_list').val()+'&count='+$('#item_count').val()+'&sid='+$('#servers').val(),function(data){
                        if(data&&data.msg!=undefined)
                        {
                            alert(data.msg);
                            $.getJSON("/gm/getMoney/"+$("#uid").val(),function(data){
                                if(data&&data.money){
                                    $.each(data.money,function(kk,vv){
                                        var mcount="#count_"+vv.id;
                                        $(mcount).val(vv.count);
                                    });
                                }
                            },'json');
                        }
                    });
                }else{
                    $('#msg').html("请先查找或选择角色ID");
                }
            });
            $('#servers').change(function(){
                $.post('/gm/updateSid',{'sid':$(this).val(),'_token':'{{csrf_token()}}'},function(){
                    location.reload();
                });
            });

        });
    </script>
@endsection
@section("style")
    @parent
    <link rel="stylesheet" href="{{"/js/chosen/tinyselect.css"}}">
    <style type="text/css">
        label{vertical-align:center;width: 90px;float:left;text-align:right;margin-right:2px;}
        input{vertical-align:left;width: 150px;float:left;}
        body{font-family:tahoma Verdana;font-size:12px;}
        a{text-decoration:none;font-size:14px;}
        table {
            font-family:Verdana, Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            table-layout:fixed;
            background-color: #F0F0F0;
        }
        table tr td{
            text-align:center;
            vertical-align:middle;
            font-size: 12px;
            height:15px;
            border:1px solid green;
        }
        .tinyselect {
            float:left;
            margin-left: 60px;
        }
    </style>
@endsection
@section("backstage_content")
    <div class="content" style="float:left;" >
        <div style="float:left;" style="width: 232px;">
        <form action="">
            <fieldset style="width: 232px;height: 50px;">
                <legend>查找</legend>
                <label style="text-align:left;width: 50px;">账号ID:</label>
                <input type="text"  name="suid" id="suid" style="width: 120px;float: left;"/>
                <input type="button" name="search" id="search" value="查找" style="width: 50px;height:25px;margin-left: 2px;"/>
                <label id="error"  style="font-size: medium;color: red;"></label>
            </fieldset>
            <fieldset style="width: 230px;">
                <legend>区服列表</legend>
                <select name="servers" id="servers" style="width: 200px;height:25px;margin-left: 4px;">

                    @foreach($servers as $value)
                        <?php $selected=$value['ServerType']==session("sid")?'selected="selected"':''; ?>
                        <option <?php echo $selected;?> value="{{$value["ServerType"]}}">{{$value["ServerType"]}}区 — {{$value["text"]}}</option>
                    @endforeach
                </select>
            </fieldset>
            </fieldset>
            <fieldset style="width: 200px; float:none;"><legend>角色ID列表</legend>
                <select name="userid" id="userid" size="<?php echo count($keys)>0?count($keys)+1:2?>" style="width: 200px;height: 800px">
                    <?php
                    foreach ($keys as $value) {
                        echo "<option value=".$value.">$value</option>";
                    }
                    ?>
                </select>
            </fieldset>
        </form>
        </div>
        <div style="float: auto;">
            <form action="" id="edit" name="edit">
                <fieldset style="vertical-align: baseline;">
                    <legend >角色信息</legend>
                    <label>uid:</label>
                    <input type="text" name="uid" id="uid" class="info" readonly="readonly" />
                    <label>昵称:</label>
                    <input type="text" name="name" id="name" class="info"/>
                    <label>账号名称:</label>
                    <input type="text" name="account" id="account" class="info"/>
                    <label>玩家等级 :</label>
                    <input type="text" name="level" id="level" class="info"/><br/><br/>
                    <label>vip等级:</label>
                    <input type="text" name="viplevel" id="viplevel" class="info"/>
                    <label>创建时间:</label>
                    <input type="text" name="createtime" id="createtime" class="info"/>
                    <label>登陆时间:</label>
                    <input type="text" name="logintime" id="logintime" class="info"/>
                    <label>登出时间:</label>
                    <input type="text" name="logouttime" id="logouttime" class="info"/><br/><br/>
                    <label>全局唯一ID:</label>
                    <input type="text" name="allocidx" id="allocidx" class="info"/>
                </fieldset>
            </form>

            <form action="">
                <legend>资源包</legend>
                <fieldset id="money" name="money" style="height:200px;overflow:auto;">
                    <?php
                    $mm=1;
                    foreach ($resName as $key => $value) {
                        echo '<label id="id_'.$key.'">'.$value.':</label>
                            <input type="text" style="width:80px;" readonly="readonly" name="count_'.$key.'" id="count_'.$key.'" value="0"/>
                            <input type="text" style="width:80px;" placeholder="+" name="'.$key.'" id="addCount"/>';
                        if($mm%4==0){echo "<br/><br/>";}
                        $mm++;
                    }
                    ?>
                    <input type="button" name="addAllMoney" id="addAllMoney" value="批量添加" style="margin-left: 60px;width: 80px;height: 33px;">
                    <select name="item_list" id="item_list"  style="margin-left: 60px;width: 40px;">
                        <?php
                        foreach ($config as $value) {
                            echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
                        }
                        ?>
                    </select>
                    <select name="item_count" id="item_count"  style="width: 50px;height:32px;float: left;font-size:15px;">
                        <?php
                        for ($g=1; $g <1000; $g++) {
                            echo "<option value=".$g.">".$g."</option>";
                        }
                        ?>
                    </select>
                    <input type="button" name="add_item" id="add_item" value="添加" style="margin-left: 10px;width: 40px;height: 33px;">
                </fieldset>
            </form>

            <form action="">
                <legend>技能</legend>
                <fieldset id="skills" name="skills" style="height:120px;overflow:auto;">
                    <label>技能id:</label>
                    <input type="text" name="groupid" id="groupid"/>
                    <label>学习程度:</label>
                    <input type="text" name="study" id="study"/>
                    <label>使用次数:</label>
                    <input type="text" name="used" id="used"/>
                </fieldset>
            </form>

            <form style="overflow:auto;">
                <legend>队伍</legend>
                <fieldset style="height:155px;">
                    <table class="teams" id="teams" border="1px">
                        <tr>
                            <td rowspan="3">队伍ID</td>
                            <td colspan="15">槽位</td>
                            <td rowspan="3">当前统御</td>
                            <td rowspan="3">属性加成</td>
                        </tr>
                        <tr>
                            <td colspan="5">槽位1</td><td colspan="5">槽位2</td><td colspan="5">槽位3</td>
                        </tr>
                        <tr>
                            <th>英雄huid</th><th>征兵开始</th><th>征兵结束</th><th>征兵数量</th><th>当前兵力</th>
                            <th>英雄huid</th><th>征兵开始</th><th>征兵结束</th><th>征兵数量</th><th>当前兵力</th>
                            <th>英雄huid</th><th>征兵开始</th><th>征兵结束</th><th>征兵数量</th><th>当前兵力</th>
                        </tr>
                    </table>
                </fieldset>
            </form>

            <form action="">
                <legend>城池</legend>
                <fieldset id="citys" name="citys" style="height:120px;overflow:auto;">
                    <label>建筑id:</label>
                    <input type="text" name="cid" id="cid"/>
                    <label>等级:</label>
                    <input type="text" name="clevel" id="clevel"/>
                    <label>上次升级时间:</label>
                    <input type="text" name="upgradetime" id="upgradetime"/>
                </fieldset>
            </form>

            <form action="">
                <legend>英雄</legend>
                <fieldset style="height:300px;overflow:auto;position: relative;margin-top:0px; " id="box">
                    <table class="heros" id="heros" border="1px">
                        <tr>
                            <td rowspan="2" width="39px">唯一ID</td>
                            <td rowspan="2" width="52px">类id</td>
                            <td rowspan="2" width="29px">兵种</td>
                            <td rowspan="2" width="27px">等级</td>
                            <td rowspan="2" width="55px">经验</td>
                            <td rowspan="2" width="29px">体力</td>
                            <td rowspan="2" width="151px">体力回复</td>
                            <td colspan="3" width="166px">技能槽</td>
                            <td rowspan="2" width="200px">属性</td>
                            <td colspan="4" width="225px">ctrl</td>
                            <td rowspan="2" width="39px">操作</td>
                            <td rowspan="2" width="42px">唯一ID</td>
                        </tr>
                        <tr>
                            <td width="72px">技能槽1</td><td width="47px">技能槽2</td>
                            <td width="47px">技能槽3</td>
                            <td width="57px">是否保护</td><td width="56px">slot1锁定</td>
                            <td width="56px">slot2锁定</td><td width="56px">slot2锁定</td>
                        </tr>
                        <tr>
                            <th></th>
                            <th contenteditable="true" id="heroid_0" style="background-color:white;"></th>
                            <th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>
                            <th></th><th></th><th></th><th></th><th><b style="cursor:pointer;color:blue;"  id="hadd">添加</b></th><th></th>
                        <tr>
                    </table>
                </fieldset>
            </form>
        </div>
    </div>
    </div>

    <script src="{{asset("js/chosen/jquery-1.11.0.min.js")}}" type="text/javascript"></script>
    <script src="{{asset("js/chosen/tinyselect.js")}}"></script>
    <script type="text/javascript">
        $("#item_list").tinyselect();
        $(".searchbox").mousedown(function(){
            $(".searchicon").remove();
        });
    </script>
@endsection