<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/2
 * Time: 14:11
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeftmenuModel extends Model
{
    //指定表名
    protected $table = 'leftmenu';

    //如果该表主键非id，需要$primaryKey指定该表的主键（如果不指定主键 ，laravel 默认id 作为主键）
    protected $primaryKey = "";

    //$timestamps (默认 true) ，自动插入创建时间（created_at）和修改时间（updated_at）  设置false 关闭自动填充功能
    public $timestamps=false;

    //用fill() 插入数据时，需要通过$fillable 指定允许操作的字段
    protected $fillable = [];


}