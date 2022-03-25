<?php

use think\migration\Seeder;

class ConfigAuthMenuSeeds extends Seeder
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

        $posts = $this->table('xy_auth_menu');
        $posts->insert($data)
              ->save();
    }
}