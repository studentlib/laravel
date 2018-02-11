<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/9
 * Time: 17:11
 */

namespace App\Http\Controllers;

class LoginController extends Controller
{
    public function __construct()
    {

    }

    public function login()
    {

        return view("login.signin");
    }

}