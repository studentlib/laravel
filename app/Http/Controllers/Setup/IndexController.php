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
        return view("setup.index");
//        dd($left_menu);
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