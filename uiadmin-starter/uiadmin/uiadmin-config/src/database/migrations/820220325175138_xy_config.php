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
    }
}
