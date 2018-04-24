<?php
namespace App\Http\Controllers\Gm;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class GmController extends Controller{

    /**
     * @var Redis
     */
    private $_redis;
    //玩家基本信息
    private $ufmt;
    //玩家英雄信息
    private $hfmt;
    //队伍信息
    private $tfmt;

    public function __construct()
    {
        //玩家基本信息
        $this->ufmt=array("count"=>"9","uid"=>"I","name"=>"a","account"=>"a","createtime"=>"Q","level"=>"I","viplevel"=>"I","logintime"=>"Q","logouttime"=>"Q","allocidx"=>"Q");
        //玩家英雄信息(不包括属性)
        $this->hfmt=array(
            "count"=>"12","huid"=>"I","heroid"=>"I","arm"=>"I","level"=>"I","exp"=>"I","stamina"=>"I","staminatime"=>"Q","ctrl"=>"I","slot1"=>"I","slot2"=>"I","slot3"=>"I",
            "props"=>array("Ik/fv","2","8",2),
        );
        //队伍信息
        $this->tfmt=array(
            "count" =>"7",
            "teamid"=>"I",
            //类型，元素数量，2元素总长度,3（一维数组 ：1 二维数组：2）
            "slot1"=>array("Ihuid/Qdraftstart/Qdraftstop/Idraftcount/Itroops","5",28,1),
            "slot2"=>array("Ihuid/Qdraftstart/Qdraftstop/Idraftcount/Itroops","5",28,1),
            "slot3"=>array("Ihuid/Qdraftstart/Qdraftstop/Idraftcount/Itroops","5",28,1),
            "cost"=>"I",
            //类型，元素数量，2元素总长度,3（一维数组 ：1 二维数组：2）
            "addition"=>array("Iid/fnumber","2","8",2),
        );
    }
    /*
     * 角色信息
     */
    public  function admin()
    {
        $servers=$this->get_server_config();
        $keys=$this->getRedisKeys(50);
        //道具表
        $config=$this->get_config();
        $obj=simplexml_load_file(public_path().'/config/CoinBase.xml');
        $resName=array();
        foreach ($obj as $vl) {
            $resName[(string)$vl['ID']]=(string)$vl['Key'];
        }
        // $test=$this->getTeams("114004779");
        return view("gm.gm",["servers"=>$servers,"keys"=>$keys,"config"=>$config,"resName"=>$resName]);
    }
    /**
     * 查看队伍信息
     * @param      boolean|string  $uid    The uid
     * @param      string          $m      { parameter_description }
     */
    public function getTeams($uid=""){
        $server=$this->getRedisConfig();
        $this->getRedis($server['RIP'], $server['RIndex'],$server['RPort']);
        $team=Redis::hGetAll("role:".$uid.":team");
        if(empty($team)){
            $teams["code"]=1;
            $teams["msg"]=__("gm.getTeamsMsg");
        }else{
            foreach ($team as $k=>$v) {
                $teams[$k]=$this->upk($v,$this->tfmt);
                for($a=1 ; $a<4;$a++){
                    if ($teams[$k]["slot".$a]["draftstart"]>0) {
                        $teams[$k]["slot".$a]["draftstart"]=date("Y-m-d H:i:s",$teams[$k]["slot".$a]["draftstart"]);
                        $teams[$k]["slot".$a]["draftstop"]=date("Y-m-d H:i:s",$teams[$k]["slot".$a]["draftstop"]);
                    }
                }
                $teams[$k]["addition"]=json_encode($teams[$k]["addition"],true);
            }
        }
        echo json_encode($teams,JSON_UNESCAPED_UNICODE);
    }
    /**
     * 查看技能信息
     */
    public function getSkills($uid=""){
        $server=$this->getRedisConfig();
        $this->getRedis($server['RIP'], $server['RIndex'],$server['RPort']);
        $skill=Redis::hGetAll("role:".$uid.":skills");
        foreach ($skill as $key => $value) {
            $skills[$key]=unpack("Igroup/Istudy/Iused", $value);
        }
        echo json_encode($skills,JSON_UNESCAPED_UNICODE);
    }
    /**
     * 查看城池信息
     */
    public function getCitys($uid=""){
        $server=$this->getRedisConfig();
        $this->getRedis($server['RIP'], $server['RIndex'],$server['RPort']);
        $city=Redis::hGetAll("role:".$uid.":city");
        foreach ($city as $key => $value) {
            $citys[$key]=unpack("Iid/Ilevel/Qupgradetime", $value);
            $citys[$key]["upgradetime"]=date("Y-m-d H:i:s",$citys[$key]["upgradetime"]);
        }
        echo json_encode($citys,JSON_UNESCAPED_UNICODE);
    }
    /*
     *查询货币
     */
    public function getMoney($uid="",$m=8){
        $server=$this->getRedisConfig();
        $this->getRedis($server['RIP'], $server['RIndex'],$server['RPort']);
        $money=Redis::hGet("role:money", $uid);
        $mdata=substr($money,0,$m);
        $data=unpack("Iuid/Icount",$mdata);
        for($i=1;$i<=$data["count"];$i++){
            $str=substr($money,$m,$m+12);
            $data["money"][$i]=unpack("Iid/Qcount", $str);
            $m+=12;
        }
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
    }
    /*
     * 添加钱包 道具(钱包 道具在一起 坑啊 都不说清楚)
     */
    public  function addMoney(Request $request)
    {
        $request=$request->toArray();
        $uid=isset($request['uid'])?$request['uid']:'';
        $sid=isset($request['sid'])?$request['sid']:'';
        $money=isset($request['money'])?$request['money']:'';
        $ret=array('code'=>0,'msg'=>'');
        if($uid&&$sid&&$money)
        {
            $servers=$this->get_server_config();
            $server=$servers[$sid];
            if(isset($server['GIP'])&&isset($server['GPort']))
            {
                foreach ($money as $key => $value) {
                    $ret[$key]["info"]=$this->modify_item($server['GIP'],$server['GPort'],$uid, $key, $value);
                }
            }
            $ret['msg']=__("gm.successMsgAdd");
        }else{
            $ret['code']=1;
            $ret['msg']=__("gm.msgFalse");
        }
        echo json_encode($ret,JSON_UNESCAPED_UNICODE);
    }
    /**
     * 查询所有武将信息
     * $m 数据unpack 起始位置
     */
    public function getHeros($uid=""){
        $server=$this->getRedisConfig();
        $this->getRedis($server['RIP'], $server['RIndex'],$server['RPort']);
        $data=Redis::hGetAll("role:".$uid.":heros");
        foreach ($data as $key=>$value){
            $ret[$key]=$this->upk($value,$this->hfmt);
            $ret[$key]["staminatime"]=date("Y-m-d H:i:s",$ret[$key]["staminatime"]);
            $ret[$key]["ctrl1"]=($ret[$key]["ctrl"]>>0)&0X01;
            $ret[$key]["ctrl2"]=($ret[$key]["ctrl"]>>1)&0X01;
            $ret[$key]["ctrl3"]=($ret[$key]["ctrl"]>>2)&0X01;
            $ret[$key]["ctrl4"]=($ret[$key]["ctrl"]>>3)&0X01;
            unset($ret[$key]["ctrl"]);
            $props=$ret[$key]["props"];
            $ret[$key]["props"]="";
            foreach ($props as $k=>$v){
                $ret[$key]["props"].=$v["k"].":".round($v["v"],2)." , ";
            }
        }
        echo  json_encode($ret,JSON_UNESCAPED_UNICODE);
    }
    //添加武将
    public  function addHero()
    {
        $uid=isset($_GET['uid'])?$_GET['uid']:'';
        $sid=isset($_GET['sid'])?$_GET['sid']:$_SESSION['sid'];
        $hid=isset($_GET['hid'])?$_GET['hid']:'';
        $ret=array('code'=>0,'msg'=>'');
        if($uid&&$hid&&$sid)
        {
            $servers=$this->get_server_config();
            $server=$servers[$sid];
            if(isset($server['GIP'])&&isset($server['GPort']))
            {
                $ret["info"]=$this->modify_addhero($server['GIP'],$server['GPort'],$uid, $hid);
            }
            if($ret["info"]["code"]==24&&$ret["info"]["errno"]==0){
                $ret['msg']=__("gm.successMsgAdd");
            }else{
                $ret['code']=1;
                $ret['msg']=__("gm.msgFalseAdd");
            }
        }else{
            $ret['code']=1;
            $ret['msg']=__("gm.msgFalse");
        }
        echo json_encode($ret,JSON_UNESCAPED_UNICODE);
    }
    //修改武将等级
    public function updHero(Request $post){
        $uid=isset($post['uid'])?$post['uid']:'';
        $sid=isset($post['sid'])?$post['sid']:$_SESSION['sid'];
        $hid=isset($post["hero"]['hid'])?$post["hero"]['hid']:'';
        $level=isset($post["hero"]['level'])?$post["hero"]['level']:'';
        if($uid&&$hid&&$sid)
        {
            $servers=$this->get_server_config();
            $server=$servers[$sid];
            if(isset($server['GIP'])&&isset($server['GPort']))
            {
                $ret["info"]=$this->modify_updhero($server['GIP'],$server['GPort'],$uid, $hid,$level);
            }
            if($ret["info"]["code"]==24&&$ret["info"]["errno"]==0){
                $ret['msg']=__("gm.successMsgUpd");
            }else{
                $ret['code']=1;
                $ret['msg']=__("gm.falseMsgUpd");
            }
        }else{
            $ret['code']=1;
            $ret['msg']=__("gm.msgFalse");
        }
        echo json_encode($ret,JSON_UNESCAPED_UNICODE);
    }
    /**
     * 查询账号信息
     * uid:int 玩家ID
     * name：string 玩家昵称(9)
     * account： string 账户名()
     * createtime：long 创建时间
     * level：int  玩家等级
     * viplevel：int 玩家vip 等级
     * loginintime：long 玩家登陆时间
     * logouttime ： long 玩家登出时间
     * allocidx：long 玩家空间内全局唯一ID
     * get "role:".$uid.":attr" 查询命令
     * @return unknown
     */
    public  function getInfo($uid="")
    {
        $server=$this->getRedisConfig();
        $this->getRedis($server['RIP'], $server['RIndex'],$server['RPort']);
        $adata=Redis::get("role:".$uid.":attr");
        $data=$this->ufmt;
        $ret=$this->upk($adata,$data);
        $time=array("logintime","logouttime","createtime");
        for($n=0;$n<count($time);$n++){
            if(isset($ret[$time[$n]])&&!empty($ret[$time[$n]])||$ret[$time[$n]]!=0){
                $ret[$time[$n]]=date("Y-m-d H:i:s",$ret[$time[$n]]);
            }
        }
        echo  json_encode($ret,true);
    }
    //切换区服
    public function updateSid(Request $request)
    {
        if($request['sid']>0)
        {
            $servers=$this->get_server_config();
            if(isset($servers[$request['sid']]))
            {
                Session::put("sid",$request['sid']);
            }
        }
    }
    /*
     * 查询所有uid
     */
    private function getRedisKeys($count)
    {
        $server=$this->getRedisConfig();
        $this->getRedis($server['RIP'], $server['RIndex'],$server['RPort']);
        $keys=Redis::hGetAll('role:id');
        sort($keys);
        if(count($keys)>$count)
        {
            $keys=array_slice($keys,0,50);
        }
        return $keys;
    }
    /*
     * 背包添加道具 加道具 C000  加武将 C002 改武将等级 C004
     */
    private function modify_item($ip,$port,$uid,$tid,$count)
    {
        $pkfmt='IISSIIIIII';
        $errno=0;
        $errstr='';
        $timeout=5;
        $dt=pack($pkfmt,36,0xF1E2D3C4,0x45,0xC000,0x94,0x52,0x99,$uid,$tid,$count);
        $sock=fsockopen($ip,(int)$port,$errno,$errstr,$timeout);
        if($sock)
        {
            fwrite($sock, $dt,36);
            $rdata=fread($sock,24);
            $arr=unpack("Scode",$rdata);
            // code = 24 成功
            $arr["errno"]=$errno;
            $arr["errstr"]=$errstr;
            return $arr;
        }
    }
    /*
     * 角色添加武将 加道具 C000  加武将 C002 改武将等级 C004
     */
    private function modify_addhero($ip,$port,$uid,$tid)
    {
        $pkfmt='IISSIIIII';
        $errno=0;
        $errstr='';
        $timeout=5;
        $dt=pack($pkfmt,32,0xF1E2D3C4,0x45,0xC002,0x94,0x52,0x99,$uid,$tid);
        $sock=fsockopen($ip,(int)$port,$errno,$errstr,$timeout);
        if($sock)
        {
            fwrite($sock, $dt,32);
            $rdata=fread($sock,24);
            $arr=unpack("Scode",$rdata);
            // code = 24 成功
            $arr["errno"]=$errno;
            $arr["errstr"]=$errstr;
            return $arr;
        }
    }
    /*
     * 角色武将属性修改 加道具 C000  加武将 C002 改武将等级 C004
     */
    private function modify_updhero($ip,$port,$uid,$tid,$level)
    {
        $pkfmt='IISSIIIIII';
        $errno=0;
        $errstr='';
        $timeout=5;
        $dt=pack($pkfmt,36,0xF1E2D3C4,0x45,0xC004,0x94,0x52,0x99,$uid,$tid,$level);
        $sock=fsockopen($ip,(int)$port,$errno,$errstr,$timeout);
        if($sock)
        {
            fwrite($sock, $dt,36);
            $rdata=fread($sock,24);
            $arr=unpack("Scode",$rdata);
            // code = 24 成功
            $arr["errno"]=$errno;
            $arr["errstr"]=$errstr;
            return $arr;
        }
    }
    private function _getHeroConfig()
    {
        $hero=simplexml_load_file(public_path().'/config/Hero.xml');
        $lang=simplexml_load_file(public_path().'/config/Language.xml');
        $config=array();
        $lang_config=array();
        foreach ($lang as $vl) {
            $lang_config[(string)$vl['Key']]=(string)$vl['Value'];
        }
        foreach ($hero as $vl) {

            $config[(string)$vl['HeroID']]=$lang_config[(string)$vl['NameID']];//(string)$vl['Name'];
        }
        return $config;
    }
}