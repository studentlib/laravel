<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/5
 * Time: 13:37
 */

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\LeftmenuModel;
use App\Models\RoleModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class IndexController extends Controller
{
    /*
     * 进入设置左侧导航栏
     */
    public function index($topmenu_li, $topmenu_name = "内容")
    {
        $user = Session::all();

//        $left_menu = LeftmenuModel::where(["roleid" => $user['roleid'], "top_menu" => $topmenu_li])
//            ->select("menu_li", "redirect_url", "menu_name")
//            ->orderBy("id", "ASC")
//            ->get()->toArray();
        $sql_left='select `menu_li`, `redirect_url`, `menu_name` from gm_leftmenu where find_in_set(:roleid, `roleid`) and `top_menu`=:topmenu_li ORDER BY id ASC';
        $left_menu = DB::select($sql_left,["roleid"=>$user['roleid'],"topmenu_li"=>$topmenu_li]);
        Session::put("left_menu", $left_menu);
        Session::put("topmenu_name", $topmenu_name);

        return view("blades.index");
    }

    /*
     * 查看管理员
     */
    public function users_manage()
    {
        $users = UserModel::select("userid", "username", "rolename","password_original", "roleid", "lastlogintime", "lastloginip", "email", "realname")
            ->orderBy("userid", "ASC")
            ->get()->toArray();
        return view("setup.users_manage",["users"=>$users]);
    }

    /*
     * 进入注册管理员页面
     */
    public function add_user_index()
    {
        $roles = RoleModel::select("roleid", "rolename")->orderBy("listorder", "ASC")->get()->toArray();
        return view("setup.add_user",["roles"=>$roles]);
    }
    /*
     * 注册管理员
     */
    public function add_user()
    {
        $Request=$_POST;
        $result = array();
        if (Session::get("roleid")==1) {
            $exit = UserModel::where("username",$Request['username'])->first();
            if(is_null($exit)){
                $result = UserModel::userinsert($Request);
            }else{
                $result = "用户已存在";
            }

        }
        return json_encode($result,true);
    }

    /*
     * 修改管理员信息
     */
    public function upd_user()
    {
        $Request=$_POST;
        $result = array();
        if (Session::get("roleid")==1) {
            $result = UserModel::userupdate($Request);
        }
        return json_encode($result,true);
    }
    /*
     * 删除管理员信息
     */
    public function del_user()
    {
        $Request=$_POST;
        $result = array();
        if (Session::get("roleid")==1) {
            $result = UserModel::userupdate($Request);
        }
        return json_encode($result,true);
    }
}