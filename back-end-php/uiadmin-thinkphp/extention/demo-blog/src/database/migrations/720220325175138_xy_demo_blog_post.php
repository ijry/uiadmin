<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class XyDemoBlogPost extends Migrator
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
        // 数据表
        $table = $this->table('xy_demo_blog_post', ['engine' => 'InnoDB', 'collation' => 'utf8_general_ci', 'comment' => '' ,'id' => 'id' ,'primary_key' => ['id']]);
        $table->addColumn('uid', 'string', ['limit' => 64,'null' => true,'signed' => true,'comment' => '',])
			->addColumn('cate_id', 'string', ['limit' => 64,'null' => true,'signed' => true,'comment' => '',])
			->addColumn('name', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '名称',])
			->addColumn('title', 'string', ['limit' => 255,'null' => true,'signed' => true,'comment' => '',])
			->addColumn('description', 'string', ['limit' => 512,'null' => true,'signed' => true,'comment' => '简介',])
			->addColumn('content', 'text', ['limit' => MysqlAdapter::TEXT_LONG,'null' => true,'signed' => true,'comment' => '',])
			->addColumn('create_time', 'datetime', ['null' => true,'signed' => true,'comment' => '',])
			->addColumn('update_time', 'datetime', ['null' => true,'signed' => true,'comment' => '',])
			->addColumn('status', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => true,'signed' => true,'comment' => '',])
			->addColumn('delete_time', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => true,'signed' => true,'comment' => '删除时间',])
            ->create();

        // 数据记录
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
