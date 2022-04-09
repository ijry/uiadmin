<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class XyAuthRole extends Migrator
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
        $table = $this->table('xy_auth_role', ['engine' => 'InnoDB', 'collation' => 'utf8_general_ci', 'comment' => '' ,'id' => 'id' ,'primary_key' => ['id']]);
        $table->addColumn('pid', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => 'PID',])
			->addColumn('name', 'string', ['limit' => 32,'null' => false,'default' => '','signed' => true,'comment' => '角色标识',])
			->addColumn('title', 'string', ['limit' => 32,'null' => false,'default' => '','signed' => true,'comment' => '角色名称',])
			->addColumn('policys', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR,'null' => false,'signed' => true,'comment' => '角色的授权规则',])
			->addColumn('create_time', 'datetime', ['null' => false,'default' => null,'signed' => true,'comment' => '创建时间',])
			->addColumn('update_time', 'datetime', ['null' => false,'default' => null,'signed' => true,'comment' => '更新时间',])
			->addColumn('status', 'boolean', ['null' => false,'default' => 0,'signed' => true,'comment' => '删除状态，0已禁用1正常',])
			->addColumn('delete_time', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '删除时间',])
			->addColumn('sortnum', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '排序',])
			->addIndex(['name'], ['unique' => true,'name' => 'label'])
            ->create();
        
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
