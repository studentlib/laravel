<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/2
 * Time: 15:05
 */
namespace App\Http\Controllers;

use App\Models\TestModel;
use Illuminate\Support\Facades\DB;

class TestController extends Controller{

    public function __construct()
    {

    }

    public function test(){
        $test1=TestModel::testselect();
        $test2=DB::connection('test')->select("select * from GMactive");
        dd($test2);
    }
}