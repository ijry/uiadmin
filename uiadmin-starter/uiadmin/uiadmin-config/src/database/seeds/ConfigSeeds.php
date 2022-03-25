<?php

use think\migration\Seeder;

class ConfigSeeds extends Seeder
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
            "name" => "uiadmin.site.title",
            "title" => "网站名称",
            "value" => "UiAdmin",
            "application" => "uiadmin",
            "profile" => "prod",
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
            "profile" => "prod",
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
            "profile" => "prod",
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
            "profile" => "prod",
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
            "profile" => "prod",
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
            "profile" => "prod",
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
            "profile" => "prod",
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
            "profile" => "prod",
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
            "profile" => "prod",
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
            "profile" => "prod",
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
            "profile" => "prod",
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

        $posts = $this->table('xy_config');
        $posts->insert($data)
              ->save();
    }
}