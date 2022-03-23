<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateUserTable extends Migrator
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
        $table  =  $this->table('auth_user',
            array('engine'=>'InnoDB', 'id' => false, 'primary_key' => 'id'));
        $table->addColumn('id', 'string',array('limit'  =>  32,'default'=>'','comment'=>'ID')) // 考虑到分布式高并发这里使用guid而不是自增ID
        ->addColumn('nickname', 'string',array('limit'  =>  128,'default'=>'','comment'=>'昵称'))
        ->addColumn('avatar', 'string',array('limit'  =>  512,'default'=>'','comment'=>'头像地址'))
        ->addColumn('username', 'string',array('limit'  =>  64,'default'=>'','comment'=>'用户名，登陆使用'))
        ->addColumn('password', 'string',array('limit'  =>  64,'default'=>md5('123456'),'comment'=>'用户密码加密后')) 
        ->addColumn('roles', 'string',array('limit'  =>  512,'default'=>'','comment'=>'用户角色'))
        ->addColumn('last_login_ip', 'integer',array('limit'  =>  11,'default'=>0,'comment'=>'最后登录IP'))
        ->addColumn('last_login_time', 'datetime',array('comment'=>'最后登录时间'))
        ->addColumn('create_time', 'datetime',array('comment'=>'创建时间'))
        ->addColumn('update_time', 'datetime',array('comment'=>'更新时间'))
        ->addColumn('status', 'boolean',array('limit'  =>  1,'default'=>0,'comment'=>'删除状态，0已禁用1正常'))
        ->addIndex(array('username'), array('unique'  =>  true))
        ->create();
    }
}
