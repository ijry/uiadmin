<?php

use think\migration\Seeder;

class AuthUserSeeds extends Seeder
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

        $posts = $this->table('xy_auth_user');
        $posts->insert($data)
              ->save();
    }
}