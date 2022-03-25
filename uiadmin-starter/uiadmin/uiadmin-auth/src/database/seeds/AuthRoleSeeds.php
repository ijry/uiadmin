<?php

use think\migration\Seeder;

class AuthRoleSeeds extends Seeder
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

        $posts = $this->table('xy_auth_role');
        $posts->insert($data)
              ->save();
    }
}