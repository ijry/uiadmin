<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class XyConfig extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('xy_config', ['engine' => 'InnoDB', 'collation' => 'utf8_bin', 'comment' => '' ,'id' => 'id' ,'primary_key' => ['id']]);
        $table->addColumn('name', 'string', ['limit' => 50,'null' => false,'default' => null,'signed' => true,'comment' => '',])
			->addColumn('title', 'string', ['limit' => 50,'null' => false,'default' => null,'signed' => true,'comment' => '标题',])
			->addColumn('value', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR,'null' => true,'signed' => true,'comment' => '',])
			->addColumn('application', 'string', ['limit' => 50,'null' => false,'default' => null,'signed' => true,'comment' => '',])
			->addColumn('profile', 'string', ['limit' => 50,'null' => false,'default' => null,'signed' => true,'comment' => '',])
			->addColumn('label', 'string', ['limit' => 50,'null' => true,'signed' => true,'comment' => '',])
			->addColumn('placeholder', 'string', ['limit' => 255,'null' => true,'signed' => true,'comment' => '',])
			->addColumn('tip', 'string', ['limit' => 512,'null' => true,'signed' => true,'comment' => '',])
			->addColumn('type', 'string', ['limit' => 50,'null' => true,'signed' => true,'comment' => '表单类型',])
			->addColumn('options', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR,'null' => true,'signed' => true,'comment' => '选项',])
			->addColumn('create_time', 'datetime', ['null' => true,'signed' => true,'comment' => '创建时间',])
			->addColumn('update_time', 'datetime', ['null' => true,'signed' => true,'comment' => '修改时间',])
			->addColumn('status', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 1,'signed' => true,'comment' => '状态',])
			->addColumn('sortnum', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 1,'signed' => true,'comment' => '排序',])
			->addColumn('module', 'string', ['limit' => 50,'null' => false,'default' => null,'signed' => true,'comment' => '模块',])
            ->create();
        
        // 数据
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
    
            $postsConfig = $this->table('xy_config');
            $postsConfig->insert($data)
                ->save();
        
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

        $posts = $this->table('xy_auth_menu');
        $posts->insert($data)
                ->save();
    }
}
