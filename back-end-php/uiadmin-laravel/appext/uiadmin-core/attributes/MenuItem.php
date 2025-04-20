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

    public string $icon = '';
    public string $fullPath = '';
    public string $module = '';
    public string $title = '';
    public string $apiRule = '';
    public string $path = '';
    public string $pmenu = '';
    public string $tip = '';
    public string $menuLayer = 'admin';
    public int|string $menuType = 1;
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
    public int $isAgent = 0;
    public array $hiddenSites = [];
    public array $showSites = [];

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
        $route = [
            "icon" => $this->icon,
            "fullPath" => $this->fullPath,
            "module" => $this->module,
            "title" => $this->title,
            "apiRule" => $this->apiRule,
            "path" => $this->path,
            "pmenu" => $this->pmenu,
            "tip" => $this->tip,
            "menuLayer" => $this->menuLayer,
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
            "outUrl" => $this->outUrl,
            "isAgent" => $this->isAgent,
            "hiddenSites" => $this->hiddenSites,
            "showSites" => $this->showSites,
        ];
        if (is_string($this->menuType)) {
            switch ($this->menuType) {
                case 'cate': // 菜单一级分类
                    $route['menuType'] = -1;
                    break;
                case 'group': // 菜单分组
                    $route['menuType'] = 0;
                    break;
                case 'menu': // 菜单项目（一般是页面）
                    $route['menuType'] = 1;
                    break;
                case 'button': // 页面按钮
                    $route['menuType'] = 2;
                    break;
                case 'api': // 纯接口主要起定义路由作用
                    $route['menuType'] = 3;
                    break;
                default :
                    $route['menuType'] = $this->menuType;
                    break;
            }
        } else if(is_int($this->menuType)) {
            $route['menuType'] = $this->menuType;
        }
        self::$all[] = $route;
    }
}
