<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use DB;

class UserController extends Controller
{
    //laravel 高级查询方法学习 ： http://blog.csdn.net/fationyyk/article/details/50884244
    public function __construct(){

        
    }
    //查询数据库中的信息
    public function select(){
        //查找表中的所有信息
        //$rows=UserModel::all()->toArray();
        //get() 查找的执行函数
        //$rows=UserModel::get()->toArray();
        //dd($rows);
        //where 条件  first()取出第一条数据
        //$user=UserModel::where("uid","2")->first();
        //同first 效果差不多  ，value只取出值
        //$user=UserModel::where("uid","2")->value('name');
        //查询 需要的字段
        $user=UserModel::where("userid","1")->select("userid","username","roleid")->get()->toArray();

        dd($user);
    }

    public function insert(){
        //向数据库中插入信息
//        $user=new UserModel();
//        $user->name="隔壁老宋";
//        $user->age=30;
//        $user->roleid=110;
//        $user->uid='1100003';
//        $user->save();

        //fill() 可以一次插入多个字段信息  ：注意要在model中通过$fillable 指定允许操作的字段
//        $user=new UserModel();
//        $user->fill(["name"=>"对门老郭","age"=>"35","uid"=>"1100004","roleid"=>"2"]);
//        $user->save();

        //create() 一次插入多条数据
//        UserModel::create(["name"=>"楼上小微","age"=>"21","uid"=>"1100005","roleid"=>"2"])
//        ->create(["name"=>"楼上小微","age"=>"21","uid"=>"1100005","roleid"=>"2"])
//        ->create(["name"=>"楼下小敏","age"=>"19","uid"=>"1100006","roleid"=>"2"]);

        //
        $arr=[
            ["username"=>"laohuang","roleid"=>"1","password"=>"lll","realname"=>"老黄"],
        ];
        $status=UserModel::insert($arr);

        dd($status);
    }

    public function update(){
        //最简单的更新方式 ，从数据中取出 修改 然后再插入
//        $user=UserModel::where(["id"=>7])->first();
//        $user->name="地下室小欢";
//        $user->save();
        //优雅的修改
        UserModel::where(["name"=>"楼上小黄","id"=>11])->update(["name"=>"路边小贩"]);
        //dd($user->id);
    }

    public function del(){
        //删除数据
        UserModel::where(["id"=>18])->delete();

    }

    /**
     * @return string
     * 使用 DB 门面的方法 对数据库操作
     */
    public function show()
    {
        //传递给 select 方法的第一个参数是原生的SQL语句，第二个参数需要绑定到查询的参数绑定，通常，这些都是 where 子句约束中的值。参数绑定可以避免 SQL 注入攻击。
//        $sql='select * from l_users where name=? AND age=?';
//        $arr=DB::select($sql,["楼下小绿","22"]);
        //除了使用 ? 占位符来代表参数绑定外，还可以使用命名绑定来执行查询：
        $sql='select * from l_users where name=:name AND age=:age';
        $arr=DB::select($sql,["name"=>"楼下小红","age"=>"23"]);
        dd($arr);

        //使用 DB 门面的 insert 方法执行插入语句。
        DB::insert('insert into user (id, name) values (?, ?)', [1, 'Dayle']);

        //update 方法用于更新数据库中已存在的记录，该方法返回受更新语句影响的行数：
        $num = DB::update('update user set votes = 100 where name = ?', ['John']);
        //delete 方法用于删除数据库中已存在的记录，和 update 一样，该语句返回被删除的行数：
        $deleted = DB::delete('delete from user');

        //有些数据库语句不返回任何值，对于这种类型的操作，可以使用 DB 门面的 statement 方法：
        DB::statement('drop table user');//truncate table user

        //想要在一个数据库事务中运行一连串操作，可以使用 DB 门面的 transaction 方法，如果事务闭包中抛出异常，事务将会自动回滚。
        //如果闭包执行成功，事务将会自动提交。使用transaction 方法时不需要手动回滚或提交：
        //处理死锁:
        //  transaction 方法接收一个可选参数作为第二个参数，用于定义死锁发生时事务的最大重试次数。如果尝试次数超出指定值，会抛出异常：
        DB::transaction(function () {
            DB::table('users')->update(['votes' => 1]);

            DB::table('posts')->delete();
        }, 5);

        /**
         * 手动使用事务
         *   如果你想要手动开始事务从而对回滚和提交有一个完整的控制，可以使用 DB 门面的beginTransaction 方法：
        */
        DB::beginTransaction();

        //你可以通过 rollBack 方法回滚事务：
        DB::rollBack();

        //最后，你可以通过 commit 方法提交事务：
        DB::commit();


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