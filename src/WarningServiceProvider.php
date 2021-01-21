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
        //执行数据库迁移
        $this->loadMigrationsFrom(__DIR__.'/../migrations');

        //视图
        $this->loadViewsFrom(__DIR__.'/views', 'warning');
        $this->publishes([
            __DIR__.'/views' => resource_path('views/vendor/warning'),
        ]);

    }

    protected function registerEvents()
    {
        $events = $this->app->make(Dispatcher::class);

        foreach ($this->events as $event => $listeners) {
            foreach ($listeners as $listener) {
                $events->listen($event, $listener);
            }
        }
    }


}
