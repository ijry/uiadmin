<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXyDemoBlogPostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xy_demo_blog_post', function (Blueprint $table) {
            $table->integer('id', true)->unique('id')->comment('ID');
            $table->string('namespace', 32)->default('uiadmin')->comment('根命名空间');
            $table->string('module', 32)->default('')->comment('所属模块');
            $table->string('title', 32)->default('')->comment('名称');
            $table->string('icon', 32)->default('')->comment('图标');
            $table->string('path', 32)->default('')->comment('路由路径');
            $table->string('pmenu', 32)->default('')->comment('父菜单');
            $table->integer('sortnum')->default(0)->comment('排序');
            $table->string('menu_layer', 32)->default('')->comment('分层标记');
            $table->integer('menu_type')->default(0)->comment('菜单类型1导航2按钮3仅接口');
            $table->string('route_type', 32)->default('')->comment('路由类型');
            $table->string('api_prefix', 15)->default('')->comment('接口前缀, 一般为：v1');
            $table->string('api_suffix', 32)->default('')->comment('接口路由参数后缀');
            $table->string('api_params', 32)->default('')->comment('接口Query参数');
            $table->string('out_url')->default('')->comment('外链地址或者远程组件地址');
            $table->boolean('is_dev')->default(false)->comment('是否开发者模式才显示');
            $table->dateTime('create_time')->nullable()->comment('创建时间');
            $table->dateTime('update_time')->nullable()->comment('更新时间');
            $table->boolean('status')->default(false)->comment('删除状态，0已禁用1正常');
            $table->string('tip')->default('')->comment('Tip');
            $table->string('api_method', 32)->default('')->comment('接口请求方法');
            $table->boolean('is_hide')->default(false)->comment('是否隐藏菜单');
            $table->text('doc')->nullable()->comment('接口文档');
            $table->string('api_ext', 32)->default('')->comment('接口虚拟后缀');
            $table->boolean('delete_time')->default(false)->comment('删除时间');
        });
	}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('xxx');
    }
}
