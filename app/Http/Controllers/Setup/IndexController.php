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
use Illuminate\Support\Facades\Session;

class IndexController extends Controller{

    public function index(){
        $user = Session::all();
        $left_menu = LeftmenuModel::where("roleid", $user['roleid'])
            ->select("menu_li", "redirect_url", "menu_name")
            ->orderBy("id", "ASC")
            ->get()->toArray();
        Session::put("left_menu",$left_menu);
//        dd(Session::get("left_menu"));
        return view("setup.index");
    }

    /*
     * 注册管理员
     */
    public function register(){
        if(!$status){
            $result=UserModel::userinsert($Request);
            Session::flash("message",$result);
            return redirect("/login");
        }
    }
}