<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/17
 * Time: 18:10
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{

    /*
     * 测试
     */
    public function index(){
        return 'Hello student';
        //return redirect('student/display');
    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 测试
     */

    public function one(){
        return 'Hello 上海。';
    }

    /*
     * 测试
     */
    public function two(){
        //跳转到 页面
        return view('display');
    }

    /*
     * 测试
     */
    public function three(){
       // return 'This is action(three)';
        // redirect 跳转
        //return redirect('/student');
        // 门面模式跳转
        return Redirect::to('/student/one');
    }

    /*
     * 测试
     */
    public function four(){
        return redirect()->action('StudentController@two');

    }

    /**
     * 测试
     */

    public function five(){
        //echo '测试重定向到本地';
        return redirect('http://www.baidu.com');
        //return redirect('/student/two');
        //测试重定向到 百度
        //return redirect('http://www.baidu.com');
    }

<<<<<<< HEAD
    /**
     * 测试
     */

    public function six(Request,$Request){
        dd($Request->all());
    }

=======
>>>>>>> 5fb10aaf223e53b20ee5b6870c0e5383494ac03f


}