<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class XyAuthUser extends Migrator
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
        $table = $this->table('xy_auth_user', ['engine' => 'InnoDB', 'collation' => 'utf8_general_ci', 'comment' => '' ,'id' => 'id' ,'primary_key' => ['id']]);
        $table->addColumn('user_key', 'string', ['limit' => 32,'null' => false,'default' => '','signed' => true,'comment' => '用户密钥',])
			->addColumn('nickname', 'string', ['limit' => 32,'null' => false,'default' => '','signed' => true,'comment' => '昵称',])
			->addColumn('avatar', 'string', ['limit' => 512,'null' => false,'default' => '','signed' => true,'comment' => '头像地址',])
			->addColumn('username', 'string', ['limit' => 15,'null' => false,'default' => '','signed' => true,'comment' => '用户名，登陆使用',])
			->addColumn('password', 'string', ['limit' => 32,'null' => false,'default' => 'e10adc3949ba59abbe56e057f20f883e','signed' => true,'comment' => '用户密码加密后',])
			->addColumn('roles', 'string', ['limit' => 512,'null' => false,'default' => '','signed' => true,'comment' => '用户角色',])
			->addColumn('last_login_ip', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '最后登录IP',])
			->addColumn('last_login_time', 'datetime', ['null' => false,'default' => null,'signed' => true,'comment' => '最后登录时间',])
			->addColumn('create_time', 'datetime', ['null' => false,'default' => null,'signed' => true,'comment' => '创建时间',])
			->addColumn('update_time', 'datetime', ['null' => false,'default' => null,'signed' => true,'comment' => '更新时间',])
			->addColumn('status', 'boolean', ['null' => false,'default' => 0,'signed' => true,'comment' => '删除状态，0已禁用1正常',])
			->addColumn('delete_time', 'integer', ['limit' => MysqlAdapter::INT_REGULAR,'null' => false,'default' => 0,'signed' => true,'comment' => '删除时间',])
			->addIndex(['username'], ['unique' => true,'name' => 'username'])
            ->create();
    }
}
