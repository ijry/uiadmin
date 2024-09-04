<?php
namespace uiadmin\core\attributes;

use Attribute;

/**
 * 注解菜单路由
 *
 * @author jry <ijry@qq.com>
 */
// 声明该Route注解可以重复使用，只能绑定在类方法上
#[Attribute(Attribute::IS_REPEATABLE|Attribute::TARGET_METHOD)]
class MenuItem {

    public static $all = [];

    public string $fullPath = '';
    public string $module = '';
    public string $title = '';
    public string $path = '';
    public string $pmenu = '';
    public string $tip = '';
    public string $menuLayer = 'admin';
    public int $menuType = 1;
    public string $routeType = 'form';
    public string $apiPrefix = 'v1';
    public string $apiSuffix = '';
    public string $apiParams = '';
    public string $apiMethod = 'GET';
    public string $apiExt = '';
    public int $isHide = 0;
    public int $status = 1;
    public int $sortnum = 0;
    public string $pathSuffix = '';
    public string $outUrl = '';

    public function __construct()
    {
    }

    /**
     * @description => 添加路由
     *
     *  @author jry <ijry@qq.com>
     * @return void
     */
    public function addRoute() :void
    {
        self::$all[] = [
            "fullPath" => $this->fullPath,
            "module" => $this->module,
            "title" => $this->title,
            "path" => $this->path,
            "pmenu" => $this->pmenu,
            "tip" => $this->tip,
            "menuLayer" => $this->menuLayer,
            "menuType" => $this->menuType,
            "routeType" => $this->routeType,
            "apiPrefix" => $this->apiPrefix,
            "apiSuffix" => $this->apiSuffix,
            "apiParams" => $this->apiParams,
            "apiMethod" => $this->apiMethod,
            "apiExt" => $this->apiExt,
            "isHide" => $this->isHide,
            "status" => $this->status,
            "sortnum" => $this->sortnum,
            "pathSuffix" => $this->pathSuffix,
            "outUrl" => $this->outUrl
        ];
    }
}
