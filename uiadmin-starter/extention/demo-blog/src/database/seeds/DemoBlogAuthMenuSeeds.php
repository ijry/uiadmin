<?php

use think\migration\Seeder;

class DemoBlogAuthMenuSeeds extends Seeder
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
            "namespace" => "demo",
            "module" => "demo-blog",
            "title" => "文章管理",
            "icon" => "",
            "path" => "/blog/post/lists",
            "pmenu" => "/_tab_content",
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
            "tip" => "",
            "api_method" => "GET",
            "is_hide" => 0,
            "doc" => null,
            "api_ext" => "",
            "delete_time" => 0
          ],
          [
            "namespace" => "demo",
            "module" => "demo-blog",
            "title" => "添加文章",
            "icon" => "",
            "path" => "/blog/post/add",
            "pmenu" => "/blog/post/lists",
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
            "tip" => "",
            "api_method" => "GET|POST",
            "is_hide" => 0,
            "doc" => null,
            "api_ext" => "",
            "delete_time" => 0
          ],
          [
            "namespace" => "demo",
            "module" => "demo-blog",
            "title" => "修改",
            "icon" => "",
            "path" => "/blog/post/edit",
            "pmenu" => "/blog/post/lists",
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
            "tip" => "",
            "api_method" => "GET|PUT",
            "is_hide" => 0,
            "doc" => null,
            "api_ext" => "",
            "delete_time" => 0
          ],
        );

        $posts = $this->table('xy_auth_menu');
        $posts->insert($data)
              ->save();
    }
}