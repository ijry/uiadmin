<?php

use think\migration\Seeder;

class ExtAuthMenuSeeds extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
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

        $posts = $this->table('xy_auth_menu');
        $posts->insert($data)
              ->save();
    }
}