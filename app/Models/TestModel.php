<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/2
 * Time: 14:26
 */
 namespace App\Models;

 use Illuminate\Database\Eloquent\Model;

 class TestModel extends Model{

     // 数据库'dadtabase_center'中的users表
     protected $connection = 'test';
     protected $table = "active";

     public static function testselect()
     {
         $active = TestModel::get()->toArray();
         return $active;
     }

 }