<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXyAuthUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xy_auth_user', function (Blueprint $table) {
            $table->integer('id', true)->comment('ID');
            $table->string('user_key', 32)->default('')->comment('用户密钥');
            $table->string('nickname', 32)->default('')->comment('昵称');
            $table->string('avatar', 512)->default('')->comment('头像地址');
            $table->string('username', 15)->default('')->unique('username')->comment('用户名，登陆使用');
            $table->string('password', 32)->default('e10adc3949ba59abbe56e057f20f883e')->comment('用户密码加密后');
            $table->string('roles', 512)->default('')->comment('用户角色');
            $table->integer('last_login_ip')->default(0)->comment('最后登录IP');
            $table->dateTime('last_login_time')->comment('最后登录时间');
            $table->dateTime('create_time')->comment('创建时间');
            $table->dateTime('update_time')->comment('更新时间');
            $table->boolean('status')->default(false)->comment('删除状态，0已禁用1正常');
            $table->integer('delete_time')->default(0)->comment('删除时间');
        });

        // 数据
        $data = array(
            array(
                "id" => 1,
                "user_key" => "uiadmin",
                "nickname" => "admin",
                "avatar" => "",
                "username" => "admin",
                "password" => user_pwd_md5('uiadmin', 'uiadmin'),
                "roles" => "super_admin",
                "last_login_ip" => 0,
                "last_login_time" => date('Y-m-d H:i:s'),
                "create_time" => date('Y-m-d H:i:s'),
                "update_time" => date('Y-m-d H:i:s'),
                "status" => 1
            )
        );
        \Illuminate\Support\Facades\DB::table('xy_auth_user')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('xy_auth_user');
    }
}
