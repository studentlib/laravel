<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use DB;

class UserController extends Controller
{
    
    public function __construct(){

        
    }

    public function show(){
        $sql='select * from users';
        $arr=DB::select($sql);
        $test=['1'=>'a','2'=>['3'=>'c']];
        dd($test);
        print_r($arr);
        var_dump($arr);
        dd($arr);
        return 'Hello laravel';
    }
    
    public function getId($id=111){

        return 'id:'.$id.PHP_EOL;
    }
    
    public function getName($name='rick'){
        return 'name:'.$name.PHP_EOL;
    }

    public function namespace($id='123'){
        $url=route('namespace');
        echo $id.PHP_EOL;
        return '<a href="'.$url.'" style="text-decoration:none;color:red" >指定命名路由地址</a>';
    }
    
}

?>