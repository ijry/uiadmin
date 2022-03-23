<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateRoleTable extends Migrator
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
        $table  =  $this->table('auth_role',
            array('engine'=>'InnoDB', 'id' => false, 'primary_key' => 'id'));
        $table->addColumn('id', 'string',array('limit'  =>  32,'default'=>'','comment'=>'ID'))
        ->addColumn('label', 'string',array('limit'  =>  32,'default'=>'','comment'=>'角色标识'))
        ->addColumn('name', 'string',array('limit'  =>  32,'default'=>'','comment'=>'角色名称'))
        ->addColumn('policys', 'text',array('comment'=>'角色的授权规则'))
        ->addColumn('create_time', 'datetime',array('comment'=>'创建时间'))
        ->addColumn('update_time', 'datetime',array('comment'=>'更新时间'))
        ->addColumn('status', 'boolean',array('limit'  =>  1,'default'=>0,'comment'=>'删除状态，0已禁用1正常'))
        ->addIndex(array('label'), array('unique'  =>  true))
        ->create();
    }
}
