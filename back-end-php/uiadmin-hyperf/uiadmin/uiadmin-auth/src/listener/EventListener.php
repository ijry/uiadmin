<?php
namespace uiadmin\auth\listener;

use App\Event\UserRegistered;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\HttpServer\Router\Router;
use uiadmin\auth\model\Menu as MenuModel;

/**
 * @Listener 
 */
class EventListener implements ListenerInterface
{
    public function listen(): array
    {
        // 返回一个该监听器要监听的事件数组，可以同时监听多个事件
        return [
            \Hyperf\Framework\Event\OnManagerStart::class,
        ];
    }

    /**
     * @param UserRegistered $event
     */
    public function process(object $event) :void
    {
        // 安装auth扩展后设置驱动为auth
        // $uiadmin_config = config('uiadmin');
        // $uiadmin_config['user']['driver'] = 'uiadmin\\auth\\service\\User';
        // $uiadmin_config['menu']['driver'] = 'uiadmin\\auth\\service\\Menu';
        // config($uiadmin_config , 'uiadmin');

        // 这里是后台路由注册
        // $event->user;
        if (env('UIADMIN_INSTALL')) {
            $dataList = MenuModel::where('status', '=' , 1)
                ->where('menu_layer', '=' , 'admin')
                ->whereIn('menu_type', [1,2,3])
                ->get();
            // var_dump($dataList);
            foreach ($dataList as $key => $val) {
                if (\uiadmin\core\util\Str::startsWith($val['path'], '/')) {
                    $path = explode('/', $val['path']);
                    if (isset($path[3])) {
                            $apiSuffix = explode('/:', $val->api_suffix);
                            $apiSuffixReal = '';
                            unset($apiSuffix[0]);
                            foreach ($apiSuffix as $key => $value) {
                                $apiSuffixReal = $apiSuffixReal . '{' . $value . '}';
                            }
                            if (count($apiSuffix) > 0) {
                                $apiSuffixReal = '/' . $apiSuffixReal;
                            }
                            //dump($apiSuffixReal);
                            // 前后端分离路由
                            Router::addRoute(
                                explode('|', $val['api_method']),
                                config("uiadmin.site.apiPrefix") . '/' . $val->api_prefix . '/admin' . $val['path'] . $apiSuffixReal,
                                $val['namespace'] . '\\' . $path[1] . '\admin\\' . ucfirst(\uiadmin\core\util\Str::camel($path[2])) . '@' . $path[3]);
                    }
                }
            }
        }
    }
}
