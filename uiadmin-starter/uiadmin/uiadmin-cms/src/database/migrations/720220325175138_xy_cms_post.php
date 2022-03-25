<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class XyCmsPost extends Migrator
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
        $table = $this->table('xy_cms_post', ['engine' => 'InnoDB', 'collation' => 'utf8_general_ci', 'comment' => '' ,'id' => 'id' ,'primary_key' => ['id']]);
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
    }
}
