<?php
/**
 * +----------------------------------------------------------------------
 * | xycloud [ 多租户渐进式后台云 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2020 http://jiangruyi.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: jry <598821125@qq.com>
 * +----------------------------------------------------------------------
 * | 版权申明：此源码不是一个自由软件，是jry推出的私有源码，严禁在未经许可的情况下
 * | 拷贝、复制、传播、使用此源码的任意代码，如有违反，请立即删除，否则您将面临承担相应
 * | 法律责任的风险。如果需要取得官方授权，请联系官方QQ598821125。
*/

namespace uiadmin\ext\admin;

use Illuminate\Support\Facades\Request;
use uiadmin\core\admin\BaseAdmin;
use Illuminate\Support\Facades\Artisan;
use uiadmin\ext\model\Ext as ExtModel;

/**
 * 模块管理
 *
 * @author jry <ijry@qq.com>
 */
class Ext extends BaseAdmin
{
    /**
     * 安装模块
     *
     * @return \Response
     */
    public function import()
    {
        $pathdir = "";
        $path = input('get.path', '');
        if ($path) {
            $pathdir = $path . '/';
        }
        $name = input('get.name');
        // 启动事务
        // Db::startTrans();
        try {
            $dir = base_path() . EXT_DIR . '/' . $name;
            if (is_dir($dir)) {
                if (!input('get.upgrade')) {
                    $moduleInsall = file_get_contents($dir . '/composer.json');
                    $moduleInsall = json_decode($moduleInsall, true);
                    $names = explode('/', $moduleInsall['name']);
                    $extData = [
                        'type' => 'uiadmin',
                        'name' => $names[1],
                        'title' => $moduleInsall['title'],
                        'description' => $moduleInsall['description'],
                        'developer' => $moduleInsall['authors'][0]['name'],
                        'version' => $moduleInsall['version'],
                        'status' => 1,
                    ];

                    // 导入基础信息
                    ExtModel::create($extData);
                }

                // 执行命令
                Artisan::command('migrate -q', function () {
                });
                // Artisan::command('migrate -seed -q', function () {
                // });

                // 导入数据表
                // foreach ($moduleInsall['tables'] as $key => $value) {
                //     if (isset($value['tableCreate'])) {
                //         $value['tableCreate'] = implode("", $value['tableCreate']);
                //         if (false === Db::execute($value['tableCreate'])) {
                //             throw new \Exception($value['tableName'] . "创建出错", 0);
                //         }
                //     }
                //     if (isset($value['tableRows']) && count($value['tableRows']) > 0) {
                //         if (false === Db::table($value['tableName'])->insertAll($value['tableRows'])) {
                //             throw new \Exception($value['tableName'] . "添加记录出错", 0);
                //         }
                //     }
                // }

                // 导入配置
                // $this->core_config->saveAll($moduleInsall['config']);
                // 导入菜单及API
                // $this->core_menu->saveAll($moduleInsall['api']);
            } else {
                throw new \Exception("不存在", 0);
            }
            //Db::commit(); // 提交事务
            if (input('get.upgrade')) {
                return $this->return([
                    'code' => 200, 'msg' => '恭喜您，更新成功！', 'data' => [
                        'updateMenu' => true
                    ]
                ]);
            } else {
                return $this->return([
                    'code' => 200, 'msg' => '恭喜您，安装成功！', 'data' => [
                        'updateMenu' => true
                    ]
                ]);
            }
        } catch (\Exception $e) {
            //Db::rollback(); // 回滚事务
            return $this->return([
                'code' => 0, 'msg' => $e->getMessage(), 'data' => []
            ]);
        }
    }

    /**
     * 导出模块
     *
     * @return \Response
     */
    public function export()
    {
        $id = input('get.id');
        $info = ExtModel::where('id', $id)
            ->first();
        $dir = base_path() . EXT_DIR . '/' . $info['name'];
        if (is_dir($dir)) {
            // 打包下载
            $archive = new \uiadmin\core\util\Zip();
            $archive->ZipAndDownload($dir, $info['name']);
        }

        if (Request::isMethod('post')) {
            // 导出模块数据表
            // $mysqlConn = mysqli_connect(
            //     config('database.connections.mysql.hostname'),
            //     config('database.connections.mysql.username'),
            //     config('database.connections.mysql.password')
            // ) or die("Mysql连接失败！");
            // $moduleService = new \app\dev\service\Module();
            // if ($id == 'all') {
            //     $module_ids = $this->core_module->column('id');
            //     foreach ($module_ids as $key => $val) {
            //         $moduleService->export($val, $mysqlConn);
            //     }
            // } else {
            //     $moduleService->export($id, $mysqlConn);
            // }
            // mysqli_close($mysqlConn);

            return $this->return(['code' => 200, 'msg' => '导出成功', 'data' => []]);
        }
    }

    /**
     * 模块列表
     *
     * @return \Response
     */
    public function lists()
    {
        $type = input('type', '');

        // 模块列表
        $dataList = ExtModel::get()->toArray();

        // 获取本地未安装模块
        // 获取已经安装的模块名称
        $installed_names = ExtModel::get('name');
        $dirList = \uiadmin\core\util\File::get_dirs(base_path() . '/' . EXT_DIR)['dir'];
        foreach ($dirList as $key => $value) {
            if ($value == '.' || $value == '..') {
                continue;
            }
            if (!in_array($value, $installed_names)) {
                if (is_file(base_path() . EXT_DIR . '/' . $value . '/composer.json')) {
                    $moduleInsall = file_get_contents(base_path() . EXT_DIR . '/' . $value . '/composer.json');
                    $moduleInsall = json_decode($moduleInsall, true);
                    $names = explode('/', $moduleInsall['name']);
                    $module = [
                        'id' => '',
                        'name' => $value,
                        'type' => 'uiadmin',
                        'name' => $names[1],
                        'title' => $moduleInsall['title'],
                        'description' => $moduleInsall['description'],
                        'developer' => $moduleInsall['authors'][0]['name'],
                        'version' => $moduleInsall['version'],
                        'status' => 1,
                    ];
                    $dataList[] = $module;
                }
            }
        }
        if ($type == 'local' || $type == '') {
            $preRightButtonList = [];
            $preRightButtonList[] = [
                'name' => 'install',
                'title' => '安装',
                'pageData' => [
                    'formMethod' => 'post',
                    'api' => '/v1/admin/ext/ext/import',
                    'title' => '确认要安装该模块吗？',
                    'apiSuffix' => [],
                    'querySuffix' => [
                        ['name', 'name']
                    ],
                    'modalType' => 'confirm',
                    'width' => '600',
                    'okText' => '立即安装',
                    'cancelText' => '取消安装',
                    'content' => '<p>安装模块后您将可以使用该模块的功能</p>',
                ],
                'style' => ['size' => 'small', 'type' => 'success']
            ];
            // $preRightButtonList[] = [
            //     'name' => 'config',
            //     'title' => '配置',
            //     'pageData' => [
            //         'pageType' => 'modal',
            //         'modalType' => 'list',
            //         'api' => '/v1/admin/dev/config/lists',
            //         'width' => '1000',
            //         'apiSuffix' => ['name'],
            //         'title' => '模块配置管理'
            //     ],
            //     'style' => ['size' => 'small']
            // ];
            $preRightButtonList[] = [
                'name' => 'upgrade',
                'title' => '更新',
                'pageData' => [
                    'formMethod' => 'post',
                    'api' => '/v1/admin/ext/ext/import',
                    'apiSuffix' => [],
                    'querySuffix' => [
                        ['name', 'name']
                    ],
                    'queryParams' => 'upgrade=1',
                    'modalType' => 'confirm',
                    'width' => '600',
                    'okText' => '立即更新',
                    'cancelText' => '取消更新',
                    'content' => '<p>更新主要是更新数据库升级信息</p>',
                ],
                'style' => ['size' => 'small']
            ];
            $preRightButtonList[] = [
                'name' => 'edit',
                'title' => '修改',
                'pageData' => [
                    'pageType' => 'modal',
                    'modalType' => 'form',
                    'api' => '/v1/admin/ext/ext/edit',
                    'width' => '1000',
                    'title' => '修改模块信息'
                ],
                'style' => ['size' => 'small']
            ];
            $preRightButtonList[] = [
                'name' => 'export',
                'title' => '导出',
                'pageData' => [
                    'url' => (string)url('/api/v1/admin/ext/ext/export', [], true, true),
                    'modalType' => 'url',
                    'apiSuffix' => [],
                    'querySuffix' => [['id', 'id']],
                    'title' => '确认要导出模块吗？'
                ],
                'style' => ['size' => 'small']
            ];
            foreach ($dataList as $key => &$value) {
                // 右侧按钮
                if (!$value['id']) {
                    $value['rightButtonList'] = ['install'];
                } else {
                    $value['rightButtonList'] = ['upgrade', 'edit', 'export'];
                }
            }

            // 构造动态页面数据
            $xyBuilderList = new \uiadmin\core\util\xybuilder\XyBuilderList();
            $listData = $xyBuilderList->init()
                ->addTopButton('add', '创建新扩展', ['api' => '/v1/admin/ext/ext/add'])
                ->addRightButtons($preRightButtonList)
                ->addColumn('id' , 'ID', ['width' => '90px'])
                ->addColumn('name', '名称', ['width' => '150px'])
                ->addColumn('title', '标题', ['width' => '180px'])
                ->addColumn('description', '描述', ['width' => '180px'])
                ->addColumn('developer', '开发者', ['width' => '80px'])
                ->addColumn('version', '版本', ['width' => '80px'])
                //->addColumn('build', 'Build', ['width' => '150px'])
                ->addColumn('sortnum', '排序', ['width' => '80px'])
                ->addColumn('status' , '状态', [
                    'width' => '80px',
                    'type' => 'template',
                    'template' => 'switch',
                    'options' => [ 1 => '正常', 0 => '禁用']
                ])
                ->addColumn('rightButtonList', '操作', [
                    'minWidth' => '260px',
                    'type' => 'template',
                    'template' => 'rightButtonList'
                ])
                ->setTableName('xy_ext')
                ->setDataList($dataList)
                ->setConfig('listStyle', 'block')
                ->setConfig('listAsBlock', [
                    'title' => [
                        'field' => 'title'
                    ],
                    'titleRight' => [
                        'field' => 'version'
                    ],
                    'info' => [
                        'field' => 'developer'
                    ]
                ])
                ->getData();
            // $tree      = new Tree();
            // $listData['dataList'] = $tree->list2tree($listData['dataList'], 'name', 'pname', 'children', 0, false);
        } else if ($type == 'server') {
            $stream_opts = [
                "ssl" => [
                        "verify_peer" => false,
                        "verify_peer_name" => false,
                ]
            ];
            $res = file_get_contents(get_config('uidomain') . '/api/v1/ext/addon/lists?framework=laravel', false, stream_context_create($stream_opts));

            if ($res) {
                $res = json_decode($res, true);
                $dataList = $res['data']['dataList'];
            } else {
                // 返回数据
                return $this->return(
                    [
                        'code' => 0, 'msg' => '连接插件市场出错', 'data' => []
                    ]
                );
            }

            // 构造动态页面数据
            $xyBuilderList = new \uiadmin\core\util\xybuilder\XyBuilderList();
            $listData = $xyBuilderList->init()
                // ->addTopButton('login', '登录插件市场', ['api' => '/v1/admin/ext/server/login'])
                // ->addRightButton('down', '下载', [
                //     'pageData' => [
                //         'api' => '/v1/admin/ext/server/down',
                //         'title' => '确认要下载该扩展吗？',
                //         'modalType' => 'confirm',
                //         'formMethod' => 'post',
                //         'width' => '600',
                //         'noRefresh' => true,
                //         'okText' => '确认导出',
                //         'cancelText' => '取消操作',
                //         'content' => '<p>下载后点击安装</p>',
                //     ],
                //     'condition' => [
                //         ['isExist', '=', '0']
                //     ]
                // ])
                ->addRightButton('down', '下载', [
                    'url' => get_config('uidomain') . '/ext',
                    'modalType' => 'url',
                    'condition' => [
                        //['isExist', '=', '0']
                    ]
                ])
                ->addColumn('id' , 'ID', ['width' => '90px'])
                ->addColumn('cover', '封面', [
                    'type' => 'image',
                    'width' => '150px'
                ])
                ->addColumn('name', '名称', ['width' => '150px'])
                ->addColumn('title', '标题', ['width' => '180px'])
                //->addColumn('description', '描述', ['width' => '180px'])
                ->addColumn('userNickname', '开发者', ['width' => '80px'])
                ->addColumn('lastVersion.version', '版本', ['width' => '80px'])
                // ->addColumn('isExist' , '状态', [
                //     'width' => '80px',
                //     'type' => 'tag',
                //     'options' => [ 1 => '已下载', 0 => '未下载']
                // ])
                ->addColumn('price', '价格', ['width' => '100px'])
                ->addColumn('rightButtonList', '操作', [
                    'minWidth' => '260px',
                    'type' => 'template',
                    'template' => 'rightButtonList'
                ])
                ->setDataList($dataList)
                ->getData();
        }

        if ($type  == '') {
            // 动态TAB
            $xyBuilderTab = new \uiadmin\core\util\xybuilder\XyBuilderTab();
            $tabData = $xyBuilderTab->init()
                ->addTabs([
                    [
                        'title' => '本地扩展',
                        'list' => [
                            [
                                'title' => '',
                                'pageData' => [
                                    'modalType' => 'list',
                                    'apiBlank' => '',
                                    'api'  => '/v1/admin/ext/ext/lists',
                                    'show' => false,
                                    'queryParams' => 'type=local'
                                ],
                                'predata' => $listData
                            ],
                        ],
                    ],
                    [
                        'title' => '扩展市场',
                        'list' => [
                            [
                                'title' => '',
                                'pageData' => [
                                    'modalType' => 'list',
                                    'apiBlank' => '',
                                    'api'  => '/v1/admin/ext/ext/lists',
                                    'show' => false,
                                    'queryParams' => 'type=server'
                                ]
                            ],
                        ],
                    ]
                ])
                ->getData();

            // 返回数据
            return $this->return(
                [
                    'code' => 200, 'msg' => '成功', 'data' => [
                        'tabData' => $tabData
                    ]
                ]
            );
        } else {
            // 返回数据
            return $this->return(
                [
                    'code' => 200, 'msg' => '成功', 'data' => [
                        'listData' => $listData
                    ]
                ]
            );
        }
    }

    /**
     * 添加
     *
     * @return \Response
     */
    public function add()
    {
        if (Request::isMethod('post')) {
            // 数据验证
            $this->validateMake([
                'name'  => 'required',
                'title' => 'required',
                'description' => 'required',
                'developer' => 'required',
                'version' => 'required',
            ],
            [
                'name.require' => '模块名称必须',
                'title.require' => '模块标题必须',
                'description.require' => '模块简介必须',
                'developer.require' => '开发者必须',
                'version.require' => '版本号必须',
            ]);
            $data = Request::input();
            $this->validateData($data);

            // 数据构造
            $dataDb = $data;
            if (count($dataDb) <= 0 ) {
                return $this->return(['code' => 0, 'msg' => '无数据提交', 'data' => []]);
            }
            $dataDb['status']   = 1;

            // 是否存在模块名称
            $exist = ExtModel::where('name', $dataDb['name'])
                ->count();
            if ($exist > 0) {
                return $this->return(['code' => 0, 'msg' => '模块名称已经存在请换一个', 'data' => []]);
            }
            if (is_dir(base_path() . EXT_DIR . '/' . $dataDb['name'])) {
                return $this->return(['code' => 0, 'msg' => '模块名称已经存在请换一个', 'data' => []]);
            }

            // 启动事务
            // Db::startTrans();
            try {
                // 创建目录
                $moduleName = $dataDb['name'];
                $dataDb['path'] = '';
                $moduleNameWithSubdir = $dataDb['path'] . $dataDb['name'];
                $buildData = [
                    '__file__'   => [
                        $moduleNameWithSubdir . '/common.php',
                        $moduleNameWithSubdir . '/LrvServiceProvider.php'
                    ],
                    '__dir__'    => [
                        $moduleNameWithSubdir . '/controller',
                        $moduleNameWithSubdir . '/admin',
                        $moduleNameWithSubdir . '/command',
                        $moduleNameWithSubdir . '/model',
                        $moduleNameWithSubdir . '/service',
                        $moduleNameWithSubdir . '/util',
                        $moduleNameWithSubdir . '/view'
                    ]
                ];
                $moduleBuilder = new \uiadmin\ext\util\Build(base_path() . EXT_DIR . '/');
                $moduleBuilder->run($buildData);

                $names = explode('-', $dataDb['name']);
                $composerContentArray = [
                    "name" => $names[0] . "/" . $dataDb['name'],
                    "title" => $dataDb['title'],
                    "description" => $dataDb['description'],
                    "version" => $dataDb['version'],
                    "type" =>"library",
                    "license" =>"",
                    "authors" =>[
                        [
                            "name" => $dataDb['developer'],
                            "email" => ""
                        ]
                    ],
                    "require" => [
                        "laravel/framework" => "^9.2"
                    ],
                    "autoload" => [
                        "psr-4" => [
                            $names[0] . "\\".$names[1]."\\" => "./"
                        ],
                        "files" =>[
                            "function.php"
                        ]
                    ],
                    "extra" => [
                        "uiadmin" => [
                        ],
                        "laravel" => [
                            "providers" =>[
                                $names[0] . "\\".$names[1]."\\LrvServiceProvider"
                            ],
                            "config" => [
                                //$names[0] =>"config.php"
                            ]
                        ]
                    ]
                ];
                $composerContent = json_encode($composerContentArray, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
                file_put_contents(base_path() . 'appext/' . $moduleNameWithSubdir . '/composer.json', $composerContent);

                // 公共方法
                $commonContent = <<<EOF
                <?php
EOF;
                file_put_contents(base_path() . 'appext/' . $moduleNameWithSubdir . '/function.php', $commonContent);

                // readme
                $readmeContent = <<<EOF
## 说明
{$dataDb['description']}
EOF;
                file_put_contents(base_path() . 'appext/' . $moduleNameWithSubdir . '/README.md', $readmeContent);

                // Service
                $serviceContent = <<<EOF
<?php
namespace {$names[0]}\\{$names[1]};

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use uiadmin\auth\model\Menu as MenuModel;

// 实现DeferrableProvider时必须提供provides方法
// class LrvServiceProvider extends ServiceProvider implements DeferrableProvider
class LrvServiceProvider extends ServiceProvider
{

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    // public function provides()
    // {
    //     return [TestService::class];
    // }

    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 一旦您的包的迁移被注册，它们将在执行 php artisan migrate 命令时自动运行。
        // 您不需要将它们导出到应用程序的 database/migrations 目录。
        \$this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
EOF;
                file_put_contents(base_path() . EXT_DIR . '/' . $moduleNameWithSubdir . '/LrvServiceProvider.php', $serviceContent);


                // 存储数据
                // ExtModel::create($dataDb);

                // Db::commit(); // 提交事务
                return $this->return(['code' => 200, 'msg' => '添加模块成功', 'data' => []]);
            } catch (\Exception $e) {
                // Db::rollback(); // 回滚事务
                return $this->return(['code' => 0, 'msg' => '添加模块失败:' . $e->getMessage(), 'data' => []]);
            }
        } else {
            // 基于标题的树状列表
            // $moduleList = ExtModel::orderBy('sortnum')
            //     ->select()->toArray();
            // $tree      = new Tree();
            // $module_tree = $tree->array2tree($moduleList, 'title', 'name', 'pname', 0, false);
            // $moduleTreeSelect = [];
            // foreach ($module_tree as $key1 => $val1) {
            //     $moduleTreeSelect[$key1]['title'] = $val1['title_show'];
            //     $moduleTreeSelect[$key1]['value'] = $val1['name'];
            // }

            // 构造动态页面数据
            $xyBuilderForm = new \uiadmin\core\util\xybuilder\XyBuilderForm();
            $formData = $xyBuilderForm->init()
                ->setFormMethod('post')
                // ->addFormItem('pname', '上级模块', 'select', 0, [
                //     'tip' => '选择上级模块',
                //     'options' => $moduleTreeSelect
                // ])
                // ->addFormItem('path', '子目录', 'text', '', [
                //     'placeholder' => '请输入模块子目录称',
                // ])
                ->addFormItem('name', '模块名称', 'text', '', [
                    'placeholder' => '请输入模块名称',
                    'tip' => '模块名称由小写英文字母加横线-组成，如demo-blog'
                ])
                ->addFormItem('title', '模块标题', 'text', '', [
                    'placeholder' => '请输入模块标题',
                    'tip' => '模块标题一般为了方便用户理解，比如商城'
                ])
                ->addFormItem('description', '简介', 'textarea', '', [
                    'placeholder' => '请输入模块简介',
                    'tip' => '稍微详细的模块功能介绍'
                ])
                ->addFormItem('developer', '开发者', 'text', '', [
                    'placeholder' => '请输入开发者名称',
                ])
                // ->addFormItem('website', '开发者网站', 'text', 'https://jiangruyi.com', [
                //     'placeholder' => '请输入开发者开发者网站',
                // ])
                ->addFormItem('version', '版本号', 'text', '', [
                    'placeholder' => '请输入版本号如1.2.0',
                    'tip' => '版本号由三位数字组成x.x.x'
                ])
                // ->addFormItem('build', 'build版本号', 'text', 'build_2021', [
                //     'placeholder' => '请输入build版本号如beta1_20190301',
                //     'tip' => 'build版本号是一个更细小的版本单位，如release_20190301'
                // ])
                // ->addFormItem('sortnum', '排序', 'text', '')
                ->addFormRule('name', [
                    ['required' => true, 'message' => '请填写名称', 'trigger' => 'blur'],
                ])
                ->addFormRule('title', [
                    ['required' => true, 'message' => '请填写用户名', 'trigger' => 'blur'],
                ])
                ->addFormRule('description', [
                    ['required' => true, 'message' => '请填写模块简介', 'trigger' => 'blur'],
                ])
                ->addFormRule('developer', [
                    ['required' => true, 'message' => '请填写开发者名称', 'trigger' => 'blur'],
                ])
                ->addFormRule('version', [
                    ['required' => true, 'message' => '请填写版本号，如0.2.0', 'trigger' => 'blur'],
                ])
                ->setFormValues()
                ->getData();

            //返回数据
            return $this->return([
                'code' => 200,
                'msg' => '成功',
                'data' => [
                    'formData' => $formData
                ]
            ]);
        }
    }

    /**
     * 修改
     *
     * @return \Response
     */
    public function edit($id)
    {
        // 模块信息
        $info = ExtModel::where('id', $id)
            ->first();
        if (Request::isMethod('put')) {
            // 数据验证
            $this->validateMake([
                'name'  => 'required',
                'title' => 'required',
                'description' => 'required',
                'developer' => 'required',
                'website' => 'required',
                'version' => 'required',
                //'build' => 'require'
            ],
            [
                'name.require' => '模块名称必须',
                'title.require' => '模块标题必须',
                'description.require' => '模块简介必须',
                'developer.require' => '开发者必须',
                'website.require' => '开发者网站必须',
                'version.require' => '版本号必须',
                //'build.require' => 'build版本号必须',
            ]);
            $data = Request::input();
            $this->validateData($data);
            $dataDb = $data;

            // 数据构造
            if (count($dataDb) <= 0 ) {
                return $this->return(['code' => 0, 'msg' => '无数据提交', 'data' => []]);
            }

            // 存储数据
            // 更新数据
            foreach ($dataDb as $key => $value) {
                if (isset($info[$key])) {
                    $info[$key] = $value;
                }
            }

            // 存储数据
            $ret = $info->save();
            if ($ret) {
                return $this->return(['code' => 200, 'msg' => '修改模块信息成功', 'data' => []]);
            } else {
                return $this->return(['code' => 0, 'msg' => '修改模块信息失败:' . $this->core_module->getError(), 'data' => []]);
            }
        } else {
            // 基于标题的树状列表
            // $moduleList = ExtModel::orderBy('sortnum')
            //     ->select()->toArray();
            // $tree      = new Tree();
            // $module_tree = $tree->array2tree($moduleList, 'title', 'name', 'pname', 0, false);
            // $moduleTreeSelect = [];
            // foreach ($module_tree as $key1 => $val1) {
            //     $moduleTreeSelect[$key1]['title'] = $val1['title_show'];
            //     $moduleTreeSelect[$key1]['value'] = $val1['name'];
            // }

            // 构造动态页面数据
            $xyBuilderForm = new \uiadmin\core\util\xybuilder\XyBuilderForm();
            $formData = $xyBuilderForm->init()
                ->setFormMethod('put')
                // ->addFormItem('pname', '上级模块', 'select', 0, [
                //     'tip' => '选择上级模块',
                //     'options' => $moduleTreeSelect
                // ])
                ->addFormItem('name', '模块名称', 'text', '', [
                    'disabled' => true,
                    'placeholder' => '请输入模块名称',
                    'tip' => '模块名称由小写英文字母加横线-组成，如demo-blog'
                ])
                ->addFormItem('title', '模块标题', 'text', '', [
                    'placeholder' => '请输入模块标题',
                    'tip' => '模块标题一般为了方便用户理解，比如商城'
                ])
                ->addFormItem('description', '简介', 'textarea', '', [
                    'placeholder' => '请输入模块简介',
                    'tip' => '稍微详细的模块功能介绍'
                ])
                ->addFormItem('developer', '开发者', 'text', '', [
                    'placeholder' => '请输入开发者名称',
                ])
                ->addFormItem('website', '开发者网站', 'text', '', [
                    'placeholder' => '请输入开发者开发者网站',
                ])
                ->addFormItem('version', '版本号', 'text', '', [
                    'placeholder' => '请输入版本号如0.2.0',
                    'tip' => '版本号由三位数字组成x.x.x'
                ])
                // ->addFormItem('build', 'build版本号', 'text', '', [
                //     'placeholder' => '请输入build版本号如beta1_20190301',
                //     'tip' => 'build版本号是一个更细小的版本单位，如release_20190301'
                // ])
                ->addFormItem('sortnum', '排序', 'text', '')
                ->addFormRule('name', [
                    ['required' => true, 'message' => '请填写名称', 'trigger' => 'blur'],
                ])
                ->addFormRule('title', [
                    ['required' => true, 'message' => '请填写用户名', 'trigger' => 'blur'],
                ])
                ->addFormRule('description', [
                    ['required' => true, 'message' => '请填写模块简介', 'trigger' => 'blur'],
                ])
                ->addFormRule('developer', [
                    ['required' => true, 'message' => '请填写开发者名称', 'trigger' => 'blur'],
                ])
                ->addFormRule('version', [
                    ['required' => true, 'message' => '请填写版本号，如0.2.0', 'trigger' => 'blur'],
                ])
                ->setFormValues($info)
                ->getData();

            // 返回数据
            return $this->return([
                'code' => 200,
                'msg' => '成功',
                'data' => [
                    'formData' => $formData
                ]
            ]);
        }
    }
}
