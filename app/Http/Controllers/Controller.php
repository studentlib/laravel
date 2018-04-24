<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    protected function get_menu($topmenu_li, $topmenu_name){
        $user = Session::all();
        $sql_left='select `menu_li`, `redirect_url`, `menu_name` from gm_leftmenu where find_in_set(:roleid, `roleid`) and `top_menu`=:topmenu_li ORDER BY id ASC';
        $left_menu = DB::select($sql_left,["roleid"=>$user['roleid'],"topmenu_li"=>$topmenu_li]);
        Session::put("left_menu", $left_menu);
        Session::put("topmenu_name", $topmenu_name);

    }

    //获取serverlist.xml
    protected  function get_server_config()
    {
        $opts = array(
            'http'=>array(
                'method'=>"GET",
                'timeout'=>10,
            )
        );
        $option=stream_context_create($opts);
        $serverlist=config('system.server_list');
        $file = file_get_contents($serverlist,  null, $option);
        $servers=simplexml_load_string($file);
        $ret=array();
        foreach ($servers->row as $v)
        {
            foreach ($v->attributes() as $k1=>$v1)
            {
                $arr[(string)$k1]=(string)$v1;
            }

            if(isset($arr['Admin'])&&$arr['Admin']==='yes')
            {
                continue;
            }
            $ret[$arr['ServerType']]=$arr;
            if(!Session::get("sid"))
            {
                Session::put("sid",1);
            }
        }
        return $ret;
    }

    //获取道具表信息
    protected  function get_config()
    {
        $items=simplexml_load_file(public_path().'/config/Item.xml');
        $lang=simplexml_load_file(public_path().'/config/LanguageCN.xml');
        $config=array();
        $lang_config=array();
        foreach ($lang as $vl) {
            $lang_config[(string)$vl['Key']]=(string)$vl['Value'];
        }

        foreach ($items as $k=>$v)
        {
            if(isset($v['ItemID'])&&isset($v['ItemName']))
            {
                $config[(string)$v['ItemID']]=array('id'=>(string)$v['ItemID'],'name'=>$lang_config[(string)$v['ItemName']],'type'=>(string)$v['ItemType']);
            }
        }
        return $config;
    }

    //获取redis配置
    protected function getRedisConfig()
    {
        Session::get("sid") ? : 1;
        $sid=Session::get("sid");
        $servers=$this->get_server_config();
        $server=array();
        if(isset($servers[$sid]))
        {
            $server=$servers[$sid];
        }else if(count($servers)){
            $skeys=array_keys($servers);
            $server=$servers[$skeys[0]];
        }
        if(!count($server))
        {
            exit('NO ACCESS');
        }
        return $server;
    }

    //配置redis参数
    protected  function getRedis($ip,$index,$port=6379)
    {
        config(["database.redis"=>["default"=>["host"=>$ip,"port"=>$port,"database"=>$index]]]);
    }
    /*
      * $data 需要解析字符串
      * $filed 数组
      */
    public function upk($data="",$field=array()){
        $type=array("I"=>4,"f"=>4,"Q"=>8);//不同数据类型所占的长度
        $offset=($field["count"]+1)*2+4;
        unset($field["count"]);
        foreach ($field as $name => $value) {
            if(is_array($value)){
                //0 类型，1 元素数量，2 元素总长度,3（一维数组 ：1 二维数组：2）
                if ($value[3]==1) {
                    //一维数组
                    $offset+=($value[1]+1)*2+4;
                    $str=substr($data,$offset,$value[2]);
                    $ret[$name]= unpack($value[0],$str);
                    $offset+=$value[2];
                }else{
                    //二维数组
                    $str=substr($data,$offset,4);
                    $len=unpack("I",$str);//元素个数
                    $offset+=4;
                    if ($len[1]<1) {
                        $ret[$name]=array();
                    }else{
                        for ($l=0; $l < $len[1]; $l++) {
                            $offset+=($value[1]+1)*2+4;
                            $str=substr($data,$offset,$value[2]);
                            if ($len[1]>1){
                                $ret[$name][$l]= unpack($value[0],$str);
                            }else{
                                $ret[$name]= unpack($value[0],$str);
                            }
                            $offset+=$value[2];
                        }
                    }
                }
            }else{
                switch ($value) {
                    case 'a':
                        $str=substr($data,$offset,4);
                        $len = unpack("I", $str);
                        $offset += 4;
                        $str=substr($data,$offset,$len[1]);
                        $arr=unpack("a".$len[1], $str);
                        $ret[$name] = $arr["1"];
                        $offset += $len[1];
                        break;
                    default:
                        $str=substr($data,$offset,$type[$value]);
                        $arr=unpack($value, $str);
                        if ($value=="f") {
                            $arr[1]=round($arr[1],2);
                        }
                        $ret[$name] = $arr[1];
                        $offset += $type[$value];
                }
            }
        }
        return $ret;
    }

}
