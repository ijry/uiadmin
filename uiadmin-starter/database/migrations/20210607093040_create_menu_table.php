<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateMenuTable extends Migrator
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
        $table  =  $this->table('auth_menu',
            array('engine'=>'InnoDB', 'id' => false, 'primary_key' => 'id'));
        $table->addColumn('id', 'string',array('limit'  =>  32,'default'=>'','comment'=>'ID'))
        ->addColumn('title', 'string',array('limit'  =>  32,'default'=>'','comment'=>'名称'))
        ->addColumn('icon', 'string',array('limit'  =>  32,'default'=>'','comment'=>'图标'))
        ->addColumn('api_rule', 'string',array('limit'  =>  63,'default'=>'','comment'=>'自定义路由规则，与pathinfo规则同时存在'))
        ->addColumn('path', 'string',array('limit'  =>  32,'default'=>'','comment'=>'路由路径'))
        ->addColumn('pmenu', 'string',array('limit'  =>  32,'default'=>'','comment'=>'父菜单'))
        ->addColumn('sortnum', 'integer',array('limit'  =>  11,'default'=>0,'comment'=>'排序'))
        ->addColumn('menu_layer', 'string',array('limit'  =>  1,'default'=> '','comment'=>'分层标记'))
        ->addColumn('menu_type', 'integer',array('limit'  =>  11,'default'=>0,'comment'=>'菜单类型1导航2按钮3仅接口'))
        ->addColumn('route_type', 'string',array('limit'  =>  32,'default'=>'','comment'=>'路由类型'))
        ->addColumn('api_prefix', 'string',array('limit'  =>  15,'default'=>'','comment'=>'接口前缀, 一般为：v1'))
        ->addColumn('api_suffix', 'string',array('limit'  =>  32,'default'=>'','comment'=>'接口路由参数后缀'))
        ->addColumn('api_params', 'string',array('limit'  =>  32,'default'=>'','comment'=>'接口Query参数'))
        ->addColumn('api_method', 'string',array('limit'  =>  32,'default'=>'','comment'=>'接口请求方法'))
        ->addColumn('api_ext', 'string',array('limit'  =>  16,'default'=>'','comment'=>'接口后缀虚拟扩展名'))
        ->addColumn('out_url', 'string',array('limit'  =>  255,'default'=>'','comment'=>'外链地址或者远程组件地址'))
        ->addColumn('is_dev', 'boolean',array('limit'  =>  1,'default'=>0,'comment'=>'是否开发者模式才显示'))
        ->addColumn('create_time', 'datetime',array('comment'=>'创建时间'))
        ->addColumn('update_time', 'datetime',array('comment'=>'更新时间'))
        ->addColumn('status', 'boolean',array('limit'  =>  1,'default'=>0,'comment'=>'删除状态，0已禁用1正常'))
        ->addIndex(array('id'), array('unique'  =>  true))
        ->create();
    }
}
