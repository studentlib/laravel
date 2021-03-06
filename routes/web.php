<?php

/**
 * 登录url
 */
Route::group(["prefix"=>"login"],function(){
    //登录页
    Route::get('/','LoginController@login');
    //验证账号密码
    Route::post('/checkLogin','LoginController@checkLogin');
    //进入后台首页
    Route::get('first','LoginController@first');
});
//退出登录 ，删除session 内容
Route::get('/logout','LoginController@logout');

//设置
Route::group(["namespace"=>"Setup"],function(){
    //加载左侧菜单栏
    Route::get('/setup/left/{topmenu_li}',"IndexController@index");
    //会员中心
    Route::group(["prefix"=>"setup"],function (){
        //管理员管理（查）
        Route::get('/users_manage',"IndexController@users_manage");
        //添加管理员
        Route::get('/add_user_index',"IndexController@add_user_index");
        Route::post('/add_user',"IndexController@add_user");
        //修改管理员信息
        Route::post('/upd_user',"IndexController@upd_user");
        //删除管理员信息
        Route::post('/del_user',"IndexController@del_user");

    });
});

//角色信息管理
Route::group(["namespace"=>"Gm"],function(){
    // 查询
    Route::group(["prefix"=>"gm"],function (){
        //切换区服
        Route::post('/updateSid',"GmController@updateSid");
        //进入角色查询页面
        Route::get('/index',"GmController@admin");
        //查看玩家基本信息
        Route::get('/getInfo/{uid}',"GmController@getInfo");
        //查看英雄基本信息
        Route::get('/getHeros/{uid}',"GmController@getHeros");
        //查看资源包基本信息
        Route::get('/getMoney/{uid}',"GmController@getMoney");
        //查看主城基本信息
        Route::get('/getCitys/{uid}',"GmController@getCitys");
        //查看技能基本信息
        Route::get('/getSkills/{uid}',"GmController@getSkills");
        //查看队伍基本信息
        Route::get('/getTeams/{uid}',"GmController@getTeams");

    });
    //增加 和 修改
    Route::group(["middleware"=>"addOrUpd"],function (){
        Route::group(["prefix"=>"gm"],function (){
            //添加武将
            Route::get('/addHero',"GmController@addHero");
            //修改武将等级
            Route::post('/updHero',"GmController@updHero");
            //为玩家添加资源
            Route::post('/addMoney',"GmController@addMoney");
        });
    });
    //删除
    Route::group(["middleware"=>"delete"],function (){
        Route::group(["prefix"=>"gm"],function (){

        });
    });
});




Route::get('/',function (){
    return view("welcome");
});
Route::get('/test','TestController@test');











Route::group(["prefix"=>"student"],function(){
    /**
     *
     */
    Route::get('/','StudentController@index');
    Route::get('one','StudentController@one');
    Route::get('two','StudentController@two');
    Route::get('three','StudentController@three');
    Route::get('four','StudentController@four');
    Route::get('five','StudentController@five');
    Route::get('six','StudentController@six');
});
//测试数据库操作
Route::group(["prefix"=>"user"],function(){
    /**
     *  测试数据库操作 DB 直接执行 sql 语句
     */
    Route::get('show','UserController@show');
    /*
     * 命名路由
     */
    Route::get('/test' , 'UserController@namespace')->name('namespace');
    //如果命名路由定义了参数，可以将该参数作为第二个参数传递给 route 函数。给定的路由参数将会自动插入到 URL 中：
    Route::get('/{id}/profile', function ($id) {
        $url = route('profile', ['id' => $id]);
        return $url;
    })->name('profile');

    /**
     * 测试数据库操作功能(增，删 ，改 ，查)
     */
    Route::get('/select','UserController@select');
    Route::get('/insert','UserController@insert');
    Route::get('/update','UserController@update');
    Route::get('/del','UserController@del');

});


// 为photos控制器 注册一个资源路由：
Route::resource('photos', 'PhotoController');
//如果有必要在默认资源路由之外添加额外的路由到资源控制器，应该在调用 Route::resource 之前定义这些路由；
//否则，通过 resource(有点问题，暂时先放一下)
//Route::get('photos/popular', 'PhotoController@method');
//Route::resource('photos', 'PhotoController');//e 方法定义的路由可能无意中优先于补充的额外路由：


////路由参数限制
//Route::get('/test/{id?}/{name?}/',function($id=654,$name='john') {
//    return 'test--id:'.$id.PHP_EOL.'--name:'.$name;
//})->where(['id'=>'[0-9]+','name'=>'[A-Za-z]+']);

////指定命名空间的方法
//
//Route::group(['namespace'=>'Npt'],function(){
//
//    Route::get('npt','NptController@test');
//
//});

//Route::get('npt','Npt\NptController@test');
//
///*
// * 路由群组：前缀 group
// *  Route::get('/user/name','UserController@getName');
// *  Route::get('/user/id','UserController@getId');
// */
// Route::group(['prefix'=>'user'],function (){
//     Route::get('name','UserController@getName');
//     Route::get('id','UserController@getId');
//
// });

/*
 * 路由群组：中间件(没明白咋用)
 */
//Route::group(['middleware' => 'auth'], function () {
//    Route::get('/', function () {
//        // 使用 Auth 中间件
//        return 123;
//    });
//
//    Route::get('/user/profile', function () {
//        // 使用 Auth 中间件
//        return 456;
//    });
//});
