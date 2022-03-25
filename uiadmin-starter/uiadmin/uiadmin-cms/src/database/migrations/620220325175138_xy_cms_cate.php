<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class XyCmsCate extends Migrator
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
        $table = $this->table('xy_cms_cate', ['engine' => 'InnoDB', 'collation' => 'utf8_general_ci', 'comment' => '分类表' ,'id' => 'id' ,'primary_key' => ['id']]);
        $table->addColumn('title', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '标题',])
			->addColumn('content', 'text', ['limit' => MysqlAdapter::TEXT_LONG,'null' => true,'signed' => true,'comment' => '内容',])
			->addColumn('cover', 'string', ['limit' => 512,'null' => false,'default' => '','signed' => true,'comment' => '封面图',])
			->addColumn('description', 'string', ['limit' => 512,'null' => false,'default' => '','signed' => true,'comment' => '简介',])
			->addColumn('icon', 'string', ['limit' => 512,'null' => false,'default' => '','signed' => true,'comment' => '图标',])
			->addColumn('name', 'string', ['limit' => 255,'null' => false,'default' => '','signed' => true,'comment' => '名称',])
			->addColumn('pid', 'string', ['limit' => 40,'null' => false,'default' => '','signed' => true,'comment' => 'PID',])
			->addColumn('status', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '状态',])
			->addColumn('uid', 'string', ['limit' => 40,'null' => false,'default' => '','signed' => true,'comment' => 'UID',])
			->addColumn('create_time', 'datetime', ['null' => true,'signed' => true,'comment' => '创建时间',])
			->addColumn('update_time', 'datetime', ['null' => true,'signed' => true,'comment' => '更新时间',])
			->addColumn('delete_time', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => true,'signed' => true,'comment' => '删除时间',])
            ->create();
    }
}
