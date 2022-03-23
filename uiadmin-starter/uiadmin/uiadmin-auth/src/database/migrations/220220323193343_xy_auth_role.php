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
        $table = $this->table('xy_auth_role', ['engine' => 'InnoDB', 'collation' => 'utf8_general_ci', 'comment' => '' ,'id' => false ,'primary_key' => ['id']]);
        $table->addColumn('id', 'string', ['limit' => 32,'null' => false,'default' => '','signed' => true,'comment' => 'ID',])
			->addColumn('label', 'string', ['limit' => 32,'null' => false,'default' => '','signed' => true,'comment' => '角色标识',])
			->addColumn('name', 'string', ['limit' => 32,'null' => false,'default' => '','signed' => true,'comment' => '角色名称',])
			->addColumn('policys', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR,'null' => false,'signed' => true,'comment' => '角色的授权规则',])
			->addColumn('create_time', 'datetime', ['null' => false,'default' => null,'signed' => true,'comment' => '创建时间',])
			->addColumn('update_time', 'datetime', ['null' => false,'default' => null,'signed' => true,'comment' => '更新时间',])
			->addColumn('status', 'boolean', ['null' => false,'default' => 0,'signed' => true,'comment' => '删除状态，0已禁用1正常',])
			->addIndex(['label'], ['unique' => true,'name' => 'label'])
            ->create();
    }
}
