<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXyAuthRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xy_auth_role', function (Blueprint $table) {
            $table->integer('id', true)->comment('ID');
            $table->integer('pid')->default(0)->comment('PID');
            $table->string('name', 32)->default('')->unique('label')->comment('角色标识');
            $table->string('title', 32)->default('')->comment('角色名称');
            $table->text('policys')->comment('角色的授权规则');
            $table->dateTime('create_time')->comment('创建时间');
            $table->dateTime('update_time')->comment('更新时间');
            $table->boolean('status')->default(false)->comment('删除状态，0已禁用1正常');
            $table->integer('delete_time')->default(0)->comment('删除时间');
            $table->integer('sortnum')->default(0)->comment('排序');
        });

        $data = array(
            [
                "id" => 1,
                "pid" => 0,
                "name" => "super_admin",
                "title" => "超级管理员",
                "policys" => "",
                "create_time" => "2022-03-25 15:04:26",
                "update_time" => "2022-03-25 15:04:26",
                "status" => 1,
                "delete_time" => 0,
                "sortnum" => 0
            ],
            [
                "id" => 2,
                "pid" => 1,
                "name" => "yunying",
                "title" => "运营部",
                "policys" => "//admin/_root_admin,/v1/admin/_tab_system,//admin/core,/v1/admin/auth/user/lists,/v1/admin/auth/user/edit,/v1/admin/auth/user/delete,/v1/admin/auth/user/add,/v1/admin/auth/user/info,/v1/admin/core/index/cleanRuntime,/v1/admin/config/config/saveBatch,/v1/admin/_tab_content",
                "create_time" => "2022-03-25 15:05:47",
                "update_time" => "2022-03-25 15:05:47",
                "status" => 1,
                "delete_time" => 0,
                "sortnum" => 0
            ]
        );
        \Illuminate\Support\Facades\DB::table('xy_auth_role')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('xy_auth_role');
    }
}
