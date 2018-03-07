<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;


/**
 * 1. 命名空间一定与文件目录对应，每个文件的首字母一定大写
 * 2. 基础模型类 ，使用use加载Illuminate\Database\Eloquent\Model
 * 3. 每个模型都需要使用成员属性$table定义表名
 * 4. 如果该表主键非id，需要$primaryKey指定该表的主键（如果不指定主键 ，laravel 默认id 作为主键）
 * 5. $timestamps (默认 true) ，自动插入创建时间（created_at）和修改时间（updated_at）  设置false 关闭自动填充功能
 */
class UserModel extends Model
{
    //指定表名
    protected $table = 'users';

    //如果该表主键非id，需要$primaryKey指定该表的主键（如果不指定主键 ，laravel 默认id 作为主键）
    protected $primaryKey = "userid";

    //$timestamps (默认 true) ，自动插入创建时间（created_at）和修改时间（updated_at）  设置false 关闭自动填充功能
    public $timestamps=true;

    //用fill() 插入数据时，需要通过$fillable 指定允许操作的字段
    protected $fillable = [];

    public static function check($username, $password)
    {
        //通过username 查找管理员信息；
        $user = UserModel::where(["username"=>$username])->first();
        $ret=array();
        if($user!=null){
                //用户存在判断密码是否正确
                if(Hash::check($password,$user->password)){
                    return true;
                }else{
                    //密码错误
                    return false;
                }
        }else{
            return false;
        }
    }

    //注册管理员
    public static function userinsert($data)
    {
        if(!is_array($data)&&is_object($data)) $data=$data->toArray();
        $user = UserModel::where("username",$data['username'])->first();
        if($user==null){
            if(empty($data['username'])||strlen($data['username'])<4)
            {
                return "用户名为空或长度小于4";
            }
            if(empty($data['password'])||strlen($data['password'])<6)
            {
                //密码长度必须大于6
                return "密码为空或长度小于6";
            }
            if(empty($data['roleid']))
            {
                return "必须设置权限";
            }
            $data['lastloginip']=$_SERVER['REMOTE_ADDR'];
            $data['lastlogintime']=date("Y-m-d H:i:s",time());
            $data['password_original']=$data['password'];
            $data['password']=Hash::make($data['password']);
            unset($data["_token"]);
            $status=UserModel::insert($data);
            if($status){
                return "注册成功";
            }else{
                return "注册失败";
            }
        }else{
            return "用户已存在";
        }
    }

}