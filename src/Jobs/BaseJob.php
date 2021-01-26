<?php

namespace Sy\Warning\Jobs;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class BaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    //发送预警内容
    public $message;

    //预警发送账号
    public $account;

    //用户或用户组id
    public $userId;

    //产品
    public $product;

    //发送至用户
    public $username;

    //故障名称
    public $warningName;

    /**
     * Create a new job instance.
     *
     * @return void
     * @param array $accountAndTpl
     */
    public function __construct($accountAndTpl,$event)
    {

        $this->message = $accountAndTpl['content'];
        $this->account = $accountAndTpl['account'];
        $this->product = $event->product;
        $this->username = $event->username;
        $this->userId = $event->userId;
        $this->warningName = $event->warningName;
    }


    /**
     * 组装将存储的预警日志数据
     *
     * @param $type
     * @return array
     */
    public function warningLog($type)
    {
        return [
            'way' => $type,
            'message' => $this->message,
            'account' => $this->account,
            'times' => 1,
            'occur_time' => date('Y-m-d H:i:s'),
            'user_id' => $this->userId,
            'uname' => $this->username,
            'product' => $this->product,
            'warning_name' => $this->warningName
        ];

    }

    /**
     * 添加缓存
     * @param $cacheData
     * @param $type
     */
    public function saveCache($cacheData,$type)
    {
        try {
            $phoneCache[] = $cacheData;
            $cache = Cache::get('warning_log_'.$type);

            if ($cache) {
                $cache = json_decode($cache, 1);

                $phoneCache = array_merge($phoneCache, $cache);
            }

            Cache::put('warning_log_'.$type, json_encode($phoneCache));
        } catch (\Exception $e) {
            msgExport(1002);
        }
    }

}
