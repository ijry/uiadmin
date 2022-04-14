<?php
namespace uiadmin\core\listener;

use App\Event\UserRegistered;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Psr\Container\ContainerInterface;
use Hyperf\HttpServer\Router\Router;
use Hyperf\Contract\ConfigInterface;

/**
 * @Listener 
 */
class EventListener implements ListenerInterface
{
    private $container;

    public function __construct(ContainerInterface $container, )
    {
        $this->container = $container;
    }

    public function listen(): array
    {
        // 返回一个该监听器要监听的事件数组，可以同时监听多个事件
        return [
            \Hyperf\Framework\Event\BeforeWorkerStart::class,
        ];
    }

    /**
     * @param UserRegistered $event
     */
    public function process(object $event) :void
    {
        if (env('UIADMIN_INSTALL')) {
            $service_list = get_ext_services();
            // foreach ($service_list as $key => $value) {
            //     $this->app->register($value);
            // }
        }

        // 路由
        // Router::redirect('/' . config("uiadmin.xyadmin.entry") . '', request()->url(true) . '/');
        // Router::get('/' . config("uiadmin.xyadmin.entry") . '/', function() {
        //     $secondsToCache = 3600;
        //     $ts = gmdate("D, d M Y H:i:s", time() + $secondsToCache) . " GMT";
        //     $ch= curl_init();
        //     curl_setopt($ch, CURLOPT_URL, 'https://admin.starideas.net/xyadmin/?version=' . get_config('xyadmin.version'));
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //     curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证
        //     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // 表示不检查证书
        //     $xyadminIndex = curl_exec($ch);
        //     curl_close($ch);
        //     return $xyadminIndex;
        // });
        
        // 根接口
        //Router::addGroup('/', function () {
            Router::get('/', 'uiadmin\core\controller\Core@index');
            Router::get('/admin/api', "uiadmin\\core\\admin\\Index@api");
            Router::post(config("uiadmin.site.apiPrefix") . '/v1/admin/core/user/login', "uiadmin\\core\\admin\\User@login");
            Router::get(config("uiadmin.site.apiPrefix") . '/v1/admin/core/index/index', "uiadmin\\core\\admin\\Index@index");
            Router::get(config("uiadmin.site.apiPrefix") . '/v1/admin/core/menu/trees', "uiadmin\\core\\admin\\Menu@trees");
            Router::get(config("uiadmin.site.apiPrefix") . '/v1/core/user/info', "uiadmin\\core\\controller\\User@info");
            Router::post(config("uiadmin.site.apiPrefix") . '/v1/core/upload/upload', "uiadmin\\core\\controller\\Upload@upload");
            Router::delete(config("uiadmin.site.apiPrefix") . '/v1/core/user/logout', "uiadmin\\core\\controller\\User@logout");
        //});
        // $ret = Router::getData();
        // var_dump($ret);
    }
}
