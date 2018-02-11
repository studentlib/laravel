<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * 1. 命名空间一定与文件目录对应，每个文件的首字母一定大写
 * 2. 基础模型类 ，使用use加载Illuminate\Database\Eloquent\Model
 * 3. 每个模型都需要使用成员属性$table定义表名
 * 4. 如果该表主键非id，需要$primaryKey指定该表的主键（如果不指定主键 ，laravel 默认id 作为主键）
 * 5. $timestamps (默认 true) ，自动插入创建时间（created_at）和修改时间（updated_at）  设置false 关闭自动填充功能
 */

class UserModel extends Model{

    protected $table='users';

    //public $timestamps=false;

    protected $fillable=["uid","roleid","name","age"];
}