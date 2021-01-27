<?php

namespace Sy\Warning;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;

class WarningServiceProvider extends ServiceProvider
{
    use EventMap;
    /**
     * 服务提供者是否延迟加载
     *
     * @var bool
     */
    protected $defer = true;


    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //将扩展包默认配置和应用的已发布副本配置合并在一起
        $this->mergeConfigFrom(
            __DIR__.'/../config/warning.php', 'warning'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerEvents();

        //引入配置文件,将会复制本扩展里面config文件夹下面的warning.php到laravel框架下的config目录下生成warning.php
        $this->publishes([
            __DIR__ . '/../config/warning.php' => config_path('warning.php'),
        ]);
        //注册数据库迁移，执行php artisan migrate会自动执行，不需要再导入到database/migrations目录
        $this->loadMigrationsFrom(__DIR__.'/../migrations');
    }

    /**
     * 注册事件和监听
     */
    protected function registerEvents()
    {
        //从容器中解析出dispatcher
        $events = $this->app->make(Dispatcher::class);

        foreach ($this->events as $event => $listeners) {
            foreach ($listeners as $listener) {
                $events->listen($event, $listener);
            }
        }
    }


}
