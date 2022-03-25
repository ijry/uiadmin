<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class XyAuthMenu extends Migrator
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
        $table = $this->table('xy_auth_menu', ['engine' => 'InnoDB', 'collation' => 'utf8_general_ci', 'comment' => '' ,'id' => 'id' ,'primary_key' => ['id']]);
        $table->addColumn('namespace', 'string', ['limit' => 32,'null' => false,'default' => 'uiadmin','signed' => true,'comment' => '根命名空间',])
			->addColumn('module', 'string', ['limit' => 32,'null' => false,'default' => '','signed' => true,'comment' => '所属模块',])
			->addColumn('title', 'string', ['limit' => 32,'null' => false,'default' => '','signed' => true,'comment' => '名称',])
			->addColumn('icon', 'string', ['limit' => 32,'null' => false,'default' => '','signed' => true,'comment' => '图标',])
			->addColumn('path', 'string', ['limit' => 32,'null' => false,'default' => '','signed' => true,'comment' => '路由路径',])
			->addColumn('pmenu', 'string', ['limit' => 32,'null' => false,'default' => '','signed' => true,'comment' => '父菜单',])
			->addColumn('sortnum', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '排序',])
			->addColumn('menu_layer', 'string', ['limit' => 32,'null' => false,'default' => '','signed' => true,'comment' => '分层标记',])
			->addColumn('menu_type', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '菜单类型1导航2按钮3仅接口',])
			->addColumn('route_type', 'string', ['limit' => 32,'null' => false,'default' => '','signed' => true,'comment' => '路由类型',])
			->addColumn('api_prefix', 'string', ['limit' => 15,'null' => false,'default' => '','signed' => true,'comment' => '接口前缀, 一般为：v1',])
			->addColumn('api_suffix', 'string', ['limit' => 32,'null' => false,'default' => '','signed' => true,'comment' => '接口路由参数后缀',])
			->addColumn('api_params', 'string', ['limit' => 32,'null' => false,'default' => '','signed' => true,'comment' => '接口Query参数',])
			->addColumn('out_url', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '外链地址或者远程组件地址',])
			->addColumn('is_dev', 'boolean', ['null' => false,'default' => 0,'signed' => true,'comment' => '是否开发者模式才显示',])
			->addColumn('create_time', 'datetime', ['null' => true,'signed' => true,'comment' => '创建时间',])
			->addColumn('update_time', 'datetime', ['null' => true,'signed' => true,'comment' => '更新时间',])
			->addColumn('status', 'boolean', ['null' => false,'default' => 0,'signed' => true,'comment' => '删除状态，0已禁用1正常',])
			->addColumn('tip', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => 'Tip',])
			->addColumn('api_method', 'string', ['limit' => 32,'null' => false,'default' => '','signed' => true,'comment' => '接口请求方法',])
			->addColumn('is_hide', 'boolean', ['null' => false,'default' => 0,'signed' => true,'comment' => '是否隐藏菜单',])
			->addColumn('doc', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR,'null' => true,'signed' => true,'comment' => '接口文档',])
			->addColumn('api_ext', 'string', ['limit' => 32,'null' => false,'default' => '','signed' => true,'comment' => '接口虚拟后缀',])
			->addColumn('delete_time', 'boolean', ['null' => false,'default' => 0,'signed' => true,'comment' => '删除时间',])
			->addIndex(['id'], ['unique' => true,'name' => 'id'])
            ->create();
    }
}
