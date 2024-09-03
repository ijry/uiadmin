<?php

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class CreateXyConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xy_config', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('name', 50);
            $table->string('title', 50)->comment('标题');
            $table->text('value')->nullable();
            $table->string('application', 50);
            $table->string('profile', 50);
            $table->string('label', 50)->nullable();
            $table->string('placeholder')->nullable();
            $table->string('tip', 512)->nullable();
            $table->string('type', 50)->nullable()->comment('表单类型');
            $table->text('options')->nullable()->comment('选项');
            $table->dateTime('create_time')->nullable()->comment('创建时间');
            $table->dateTime('update_time')->nullable()->comment('修改时间');
            $table->integer('status')->default(1)->comment('状态');
            $table->integer('sortnum')->default(1)->comment('排序');
            $table->string('module', 50)->comment('模块');
        });

        // 数据
        $data = array(
            [
                "name" => "uiadmin.site.title",
                "title" => "网站名称",
                "value" => "UiAdmin",
                "application" => "uiadmin",
                "profile" => "",
                "label" => "main",
                "placeholder" => null,
                "tip" => null,
                "type" => "text",
                "options" => null,
                "create_time" => null,
                "update_time" => null,
                "status" => 1,
                "sortnum" => 1,
                "module" => "uiadmin-core"
            ],
            [
                "name" => "uiadmin.site.logoTitle",
                "title" => "横向logo",
                "value" => "https://vkceyugu.cdn.bspapp.com/VKCEYUGU-f12e1180-fce8-465f-a4cd-9f2da88ca0e6/abb2b82c-4384-4700-83bb-2d348a8a92de.png",
                "application" => "uiadmin",
                "profile" => "",
                "label" => "main",
                "placeholder" => null,
                "tip" => null,
                "type" => "image",
                "options" => null,
                "create_time" => null,
                "update_time" => null,
                "status" => 1,
                "sortnum" => 1,
                "module" => "uiadmin-core"
            ],
            [
                "name" => "uiadmin.site.logoTitleDark",
                "title" => "反色横向logo",
                "value" => "https://vkceyugu.cdn.bspapp.com/VKCEYUGU-f12e1180-fce8-465f-a4cd-9f2da88ca0e6/32174750-a1a6-4b6d-a777-200c68fae695.png",
                "application" => "uiadmin",
                "profile" => "",
                "label" => "main",
                "placeholder" => null,
                "tip" => null,
                "type" => "image",
                "options" => null,
                "create_time" => null,
                "update_time" => null,
                "status" => 1,
                "sortnum" => 1,
                "module" => "uiadmin-core"
            ],
            [
                "name" => "uiadmin.site.favicon",
                "title" => "favicon图标",
                "value" => "",
                "application" => "uiadmin",
                "profile" => "",
                "label" => "main",
                "placeholder" => null,
                "tip" => null,
                "type" => "image",
                "options" => null,
                "create_time" => null,
                "update_time" => null,
                "status" => 1,
                "sortnum" => 1,
                "module" => "uiadmin-core"
            ],
            [
                "name" => "uiadmin.site.slogan",
                "title" => "宣传语",
                "value" => "零前端轻量级通用后台",
                "application" => "uiadmin",
                "profile" => "",
                "label" => "main",
                "placeholder" => null,
                "tip" => null,
                "type" => "text",
                "options" => null,
                "create_time" => null,
                "update_time" => null,
                "status" => 1,
                "sortnum" => 1,
                "module" => "uiadmin-core"
            ],
            [
                "name" => "uiadmin.site.keywords",
                "title" => "SEO关键字",
                "value" => "uiadmin,admin,thinkphp,vue,vue-admin",
                "application" => "uiadmin",
                "profile" => "",
                "label" => "main",
                "placeholder" => null,
                "tip" => null,
                "type" => "text",
                "options" => null,
                "create_time" => null,
                "update_time" => null,
                "status" => 1,
                "sortnum" => 1,
                "module" => "uiadmin-core"
            ],
            [
                "name" => "uiadmin.site.description",
                "title" => "SEO描述",
                "value" => "UiAdmin是一套零前端代码通用后台，采用前后端分离技术，数据交互采用json格式；通过后端Builder不需要一行前端代码就能构建一个vue+element的现代化后台；同时我们打造一了套兼容性的API标准，从ThinkPHP6.0、SpringBoot、.NET5开始，逐步覆盖Go、Node.jS等多语言框架。",
                "application" => "uiadmin",
                "profile" => "",
                "label" => "main",
                "placeholder" => null,
                "tip" => null,
                "type" => "textarea",
                "options" => null,
                "create_time" => null,
                "update_time" => null,
                "status" => 1,
                "sortnum" => 1,
                "module" => "uiadmin-core"
            ],
            [
                "name" => "uiadmin.site.copyright",
                "title" => "版权",
                "value" => "版权所有 uiadmin 2022",
                "application" => "uiadmin",
                "profile" => "",
                "label" => "main",
                "placeholder" => null,
                "tip" => null,
                "type" => "text",
                "options" => null,
                "create_time" => null,
                "update_time" => null,
                "status" => 1,
                "sortnum" => 1,
                "module" => "uiadmin-core"
            ],
            [
                "name" => "uiadmin.site.icp",
                "title" => "ICP备案号",
                "value" => "苏ICP备88888",
                "application" => "uiadmin",
                "profile" => "",
                "label" => "main",
                "placeholder" => null,
                "tip" => null,
                "type" => "text",
                "options" => null,
                "create_time" => null,
                "update_time" => null,
                "status" => 1,
                "sortnum" => 1,
                "module" => "uiadmin-core"
            ],
            [
                "name" => "uiadmin.site.regState",
                "title" => "用户注册",
                "value" => "1",
                "application" => "uiadmin",
                "profile" => "",
                "label" => "main",
                "placeholder" => null,
                "tip" => null,
                "type" => "radio",
                "options" => "[{\"title\":\"关闭\",\"value\":0},{\"title\":\"开启\",\"value\":1}]",
                "create_time" => null,
                "update_time" => null,
                "status" => 1,
                "sortnum" => 1,
                "module" => "uiadmin-core"
            ],
            [
                "name" => "uiadmin.site.state",
                "title" => "网站开关",
                "value" => "1",
                "application" => "uiadmin",
                "profile" => "",
                "label" => "main",
                "placeholder" => null,
                "tip" => null,
                "type" => "radio",
                "options" => "[{\"title\":\"关闭\",\"value\":0},{\"title\":\"开启\",\"value\":1}]",
                "create_time" => null,
                "update_time" => null,
                "status" => 1,
                "sortnum" => 1,
                "module" => "uiadmin-core"
            ]
        );
        Hyperf\DbConnection\Db::table('xy_config')->insert($data);

        // 后台菜单
        $data = array(
            [
                "namespace" => "uiadmin",
                "module" => "uiadmin-config",
                "title" => "设置管理",
                "icon" => "",
                "path" => "/config/config/lists",
                "pmenu" => "/developer",
                "sortnum" => 0,
                "menu_layer" => "admin",
                "menu_type" => 1,
                "route_type" => "list",
                "api_prefix" => "v1",
                "api_suffix" => "",
                "api_params" => "",
                "out_url" => "",
                "is_dev" => 0,
                "create_time" => null,
                "update_time" => null,
                "status" => 1,
                "tip" => "系统常用设置",
                "api_method" => "GET",
                "is_hide" => 0,
                "doc" => null,
                "api_ext" => "",
                "delete_time" => 0
                ],
                [
                "namespace" => "uiadmin",
                "module" => "uiadmin-config",
                "title" => "增加配置",
                "icon" => "",
                "path" => "/config/config/add",
                "pmenu" => "/config/config/lists",
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
                "tip" => "增加系统配置",
                "api_method" => "GET|POST",
                "is_hide" => 0,
                "doc" => null,
                "api_ext" => "",
                "delete_time" => 0
                ],
                [
                "namespace" => "uiadmin",
                "module" => "uiadmin-config",
                "title" => "修改配置",
                "icon" => "",
                "path" => "/config/config/edit",
                "pmenu" => "/config/config/lists",
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
                "tip" => "修改系统配置",
                "api_method" => "GET|PUT",
                "is_hide" => 0,
                "doc" => null,
                "api_ext" => "",
                "delete_time" => 0
                ],
                [
                "namespace" => "uiadmin",
                "module" => "uiadmin-config",
                "title" => "系统设置",
                "icon" => "",
                "path" => "/config/config/saveBatch",
                "pmenu" => "/core",
                "sortnum" => 0,
                "menu_layer" => "admin",
                "menu_type" => 1,
                "route_type" => "form",
                "api_prefix" => "v1",
                "api_suffix" => "",
                "api_params" => "",
                "out_url" => "",
                "is_dev" => 0,
                "create_time" => null,
                "update_time" => null,
                "status" => 1,
                "tip" => "批量修改系统配置的值",
                "api_method" => "GET|PUT",
                "is_hide" => 0,
                "doc" => null,
                "api_ext" => "",
                "delete_time" => 0
                ]
        );
        Hyperf\DbConnection\Db::table('xy_auth_menu')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('xy_config');
    }
}
