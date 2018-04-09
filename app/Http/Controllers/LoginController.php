<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/9
 * Time: 17:11
 */

namespace App\Http\Controllers;

use App\Models\LeftmenuModel;
use App\Models\TopmenuModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

/**
 * 注意一定加载 use Illuminate\Http\Request
 */
class LoginController extends Controller
{
    public function __construct()
    {

    }

    public function login()
    {
        if(Session::get("isLogin")){
            return redirect("/login/first");
        }else{
            return view("login.signin");
        }
    }

    public function checkLogin(Request $Request)
    {
        $username = $Request->input("username");
        $password = $Request->input("password");
        //调用model 检查账号密码是否正确
        $status = UserModel::check($username, $password);

        if ($status) {
            //使用session 保存用户登录状态
            Session::put("isLogin", "true");
            $user = UserModel::where("username", $username)
                ->select("userid","username","roleid","rolename")
                ->first()->toArray();
            \session($user);
            return redirect("/login/first");
        } else {
            //使用一次性session 保存用户错误状态
            Session::flash("message", "账户或密码错误");
            //进入后台首页
            return redirect("/login");
        }
    }
    //进入首页
    public function first(){
        $user = Session::all();
//        $top_menu = TopmenuModel::where("roleid", $user['roleid'])
//            ->select("menu_li", "redirect_url", "menu_name")
//            ->orderBy("id", "ASC")
//            ->get()->toArray();
        $sql_top='select `menu_li`, `redirect_url`, `menu_name` from gm_topmenu where find_in_set(:roleid, `roleid`) ORDER BY id ASC';
        $top_menu = DB::select($sql_top,["roleid"=>$user['roleid']]);
        Session::put("top_menu",$top_menu);
//        $left_menu = LeftmenuModel::where([find_in_set($user['roleid'], "roleid"),"top_menu"=>"user"])
//            ->select("menu_li", "redirect_url", "menu_name")
//            ->orderBy("id", "ASC")
//            ->get()->toArray();
        $sql_left='select `menu_li`, `redirect_url`, `menu_name` from gm_leftmenu where find_in_set(:roleid, `roleid`) and `top_menu`="user" ORDER BY id ASC';
        $left_menu = DB::select($sql_left,["roleid"=>$user['roleid']]);
        Session::put("topmenu_name","管理员信息");
        Session::put("left_menu",$left_menu);

        //跳转到后台首页
        return view("blades.index",['top_menu'=>$top_menu,'topmenu_name'=>'']);
    }

    public function logout()
    {
        //删除session 登录状态
        //Session::forget("isLogin");
        Session::flush();
        return redirect("/login");
    }

}