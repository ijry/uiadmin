<?php

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class CreateXyExtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xy_ext', function (Blueprint $table) {
            $table->increments('id')->comment('ID');
            $table->string('type')->default('')->comment('类型composer/uiadmin');
            $table->string('pname')->default('')->comment('父模块');
            $table->string('path', 63)->default(' ')->comment('模块路径');
            $table->string('name')->default('')->unique('name')->comment('模块名称');
            $table->string('title')->default('')->comment('模块标题');
            $table->string('description')->default('')->comment('模块描述');
            $table->string('developer')->default('')->comment('开发者');
            $table->string('website')->default('')->comment('开发者网站');
            $table->string('version')->default('')->comment('版本号');
            $table->string('build')->default('')->comment('build版本');
            $table->boolean('status')->default(false)->comment('状态');
            $table->integer('sortnum')->default(0)->comment('排序');
            $table->integer('deleteTime')->default(0)->comment('删除时间');
        });

        // 后台菜单
        $data = array(
            [
                "namespace" => "uiadmin",
                "module" => "uiadmin-ext",
                "title" => "扩展管理",
                "icon" => "",
                "path" => "/ext/ext/lists",
                "pmenu" => "/developer",
                "sortnum" => 0,
                "menu_layer" => "admin",
                "menu_type" => 1,
                "route_type" => "tab",
                "api_prefix" => "v1",
                "api_suffix" => "",
                "api_params" => "",
                "out_url" => "",
                "is_dev" => 0,
                "create_time" => null,
                "update_time" => null,
                "status" => 1,
                "tip" => "管理系统安装的功能模块",
                "api_method" => "GET",
                "is_hide" => 0,
                "doc" => null,
                "api_ext" => "",
                "delete_time" => 0
            ],
            [
                "namespace" => "uiadmin",
                "module" => "uiadmin-ext",
                "title" => "创建模块",
                "icon" => "",
                "path" => "/ext/ext/add",
                "pmenu" => "/ext/ext/lists",
                "sortnum" => 0,
                "menu_layer" => "admin",
                "menu_type" => 2,
                "route_type" => "form",
                "api_prefix" => "v1",
                "api_suffix" => "",
                "api_params" => "",
                "out_url" => "",
                "is_dev" => 0,
                "create_time" => null,
                "update_time" => null,
                "status" => 1,
                "tip" => "创建一个新模块",
                "api_method" => "GET|POST",
                "is_hide" => 0,
                "doc" => null,
                "api_ext" => "",
                "delete_time" => 0
            ],
            [
                "namespace" => "uiadmin",
                "module" => "uiadmin-ext",
                "title" => "修改模块",
                "icon" => "",
                "path" => "/ext/ext/edit",
                "pmenu" => "/ext/ext/lists",
                "sortnum" => 0,
                "menu_layer" => "admin",
                "menu_type" => 2,
                "route_type" => "form",
                "api_prefix" => "v1",
                "api_suffix" => "/:id",
                "api_params" => "",
                "out_url" => "",
                "is_dev" => 0,
                "create_time" => null,
                "update_time" => null,
                "status" => 1,
                "tip" => "修改模块信息",
                "api_method" => "GET|PUT",
                "is_hide" => 0,
                "doc" => null,
                "api_ext" => "",
                "delete_time" => 0
            ],
            [
                "namespace" => "uiadmin",
                "module" => "uiadmin-ext",
                "title" => "导出模块",
                "icon" => "",
                "path" => "/ext/ext/export",
                "pmenu" => "/ext/ext/lists",
                "sortnum" => 0,
                "menu_layer" => "admin",
                "menu_type" => 2,
                "route_type" => "from",
                "api_prefix" => "v1",
                "api_suffix" => "",
                "api_params" => "",
                "out_url" => "",
                "is_dev" => 0,
                "create_time" => null,
                "update_time" => null,
                "status" => 1,
                "tip" => "导出模块信息便于分享模块",
                "api_method" => "GET",
                "is_hide" => 0,
                "doc" => null,
                "api_ext" => "",
                "delete_time" => 0
            ],
            [
                "namespace" => "uiadmin",
                "module" => "uiadmin-ext",
                "title" => "安装模块",
                "icon" => "",
                "path" => "/ext/ext/import",
                "pmenu" => "/ext/ext/lists",
                "sortnum" => 0,
                "menu_layer" => "admin",
                "menu_type" => 2,
                "route_type" => "from",
                "api_prefix" => "v1",
                "api_suffix" => "",
                "api_params" => "",
                "out_url" => "",
                "is_dev" => 0,
                "create_time" => null,
                "update_time" => null,
                "status" => 1,
                "tip" => "导入模块信息",
                "api_method" => "POST",
                "is_hide" => 0,
                "doc" => null,
                "api_ext" => "",
                "delete_time" => 0
            ]
        );
        \Illuminate\Support\Facades\DB::table('xy_auth_menu')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('xy_ext');
    }
}
