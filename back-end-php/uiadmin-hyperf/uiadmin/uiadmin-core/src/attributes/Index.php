<?php
namespace uiadmin\core\attributes;
use think\facade\Cache;

/**
 * 注解路由处理
 *
 * @author jry <ijry@qq.com>
 */
class Index {

    /**
     * 获取所有文件
     *
     * @author jry <ijry@qq.com>
     */
    public static function scanFile($root, $dir = '') {
        global $result;
        $path = $root . $dir;
        $files = scandir($path);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                if (is_dir($path . '/' . $file)) {
                    $files = explode('-', $file);
                    $namespace = $files[0] . '\\' . $files[1];
                    $files2 = scandir($path . '/' . $file . '/src');
                    foreach ($files2 as $key2 => $value2) {
                        if (str_ends_with($value2, 'admin')
                            || str_ends_with($value2, 'eadmin')
                            || str_ends_with($value2, 'controller')) {
                            $files3 = scandir($path . '/' . $file  . '/src/' . $value2);
                            foreach ($files3 as $key3 => $value3) {
                                if (str_ends_with($value3, '.php')) {
                                    $result[] = $namespace . '\\' . $value2 . '\\' . basename($value3, '.php');
                                }
                            }
                        }
                    }
                }
                
            }
        }
        // dump($result);
        return $result;
    }

    /**
     * 获取所有文件包含的注解菜单路由
     *
     * @author jry <ijry@qq.com>
     */
    public static function getMenuItems($moduleList = []) {
        // 先看有无缓存
        $menuItems = '';
        // $menuItems = Cache::get("menuItems");
        if (!$menuItems || env('APP_DEBUG')) {
            self::scanFile(BASE_PATH, '/extention');
            $retAll = self::scanFile(BASE_PATH, '/uiadmin');
            // dump($retAll);
            foreach ($retAll as $class) {
                try {
                    self::getAttributeData(new \ReflectionClass($class));
                } catch (\ReflectionException $e) {
                    throw $e;
                }
            }
            // Cache::set('menuItems', \uiadmin\core\attributes\MenuItem::$all, 1800);
        } else {
            \uiadmin\core\attributes\MenuItem::$all = $menuItems;
        }
        // dump(\uiadmin\core\attributes\MenuItem::$all);
    }

    /**
     * 获取所有文件包含的注解菜单路由数据
     *
     * @author jry <ijry@qq.com>
     */
    public static function getAttributeData($reflection) {
        $controller = $reflection->getName();
        // 如果注解是直接绑定在类上面，那么直接从类的反射获取注解
        // $attributes = $reflection->getAttributes(\uiadmin\core\attributes\MenuItem::class);
        $methods = $reflection->getMethods();

        // 因为注解是绑定在方法上的，因此循环方法，获取方法的注解
        foreach($methods as $method){
            // 方法名
            $function = $method->getName();
            // 指定获取某个注解的数据
            $attributes = $method->getAttributes(\uiadmin\core\attributes\MenuItem::class);
            foreach ($attributes as $attribute) {
                // 拿到一个新的 Route 实例
                $route = $attribute->newInstance();
                // 拿到注解上的参数
                $params = $attribute->getArguments();
                /**
                 * [
                 *  ["module" => "", "title" => "会话列表", "path" => "/aiedu/session/lists", "pmenu" => "", "menuType" => 3, "menuLayer" => "home", "routeType" => "list", "apiSuffix" => "", "apiParams" => "", "apiMethod" => "GET", "sortnum" => 0],
                 * ]
                 */
                // 执行路由添加
                foreach ($params as $item) {
                    foreach ($item as $key => $value) {
                        $name = explode('\\', $controller);
                        $route->module = $name[0] . '-' . $name[1];
                        $route->{$key} = $value;
                    }
                    $route->fullPath = '/' . $route->apiPrefix . '/' . $route->menuLayer . $route->path;
                    $route->addRoute();
                }
            }
        }
    }
}
