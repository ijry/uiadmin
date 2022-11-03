<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class XyExt extends Migrator
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
        $table = $this->table('xy_ext', ['engine' => 'InnoDB', 'collation' => 'utf8_general_ci', 'comment' => '后台模块表' ,'id' => 'id','signed' => true ,'primary_key' => ['id']]);
        $table->addColumn('type', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '类型composer/uiadmin',])
			->addColumn('pname', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '父模块',])
			->addColumn('path', 'string', ['limit' => 63,'null' => false,'default' => ' ','signed' => true,'comment' => '模块路径',])
			->addColumn('name', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '模块名称',])
			->addColumn('title', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '模块标题',])
			->addColumn('description', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '模块描述',])
			->addColumn('developer', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '开发者',])
			->addColumn('website', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '开发者网站',])
			->addColumn('version', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '版本号',])
			->addColumn('build', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => 'build版本',])
			->addColumn('status', 'boolean', ['null' => false,'default' => 0,'signed' => true,'comment' => '状态',])
			->addColumn('sortnum', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '排序',])
			->addColumn('deleteTime', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '删除时间',])
			->addIndex(['name'], ['unique' => true,'name' => 'name'])
            ->create();
        
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
    
            $posts = $this->table('xy_auth_menu');
            $posts->insert($data)
                ->save();
    }
}
