<?php

namespace Sy\Warning\Listeners;

use Sy\Warning\WarningRepository;
use Illuminate\Support\Facades\Cache;

class WarningSendListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle()
    {
        //读缓存，并写入数据库
        $phoneCache = Cache::get('warning_log_phone');
        $emailCache = Cache::get('warning_log_email');
        $dingCache = Cache::get('warning_log_ding');
        $webhookCache = Cache::get('warning_log_webhook');
        $weixinCache = Cache::get('warning_log_weixin');

        if ($phoneCache || $emailCache || $dingCache || $webhookCache || $weixinCache) {
            $pCache = $phoneCache ? json_decode($phoneCache, 1) : [];
            $eCache = $emailCache ? json_decode($emailCache, 1) : [];
            $dCache = $dingCache ? json_decode($dingCache, 1) : [];
            $wCache = $webhookCache ? json_decode($webhookCache, 1) : [];
            $wxCache = $weixinCache ? json_decode($weixinCache, 1) : [];

            $cache = array_merge($eCache, $pCache, $dCache, $wCache, $wxCache);

            $log = [];
            foreach ($cache as $c) {
                $log[] = [
                    'uid' => isset($c['user_id']) ? $c['user_id'] : '',
                    'name' => isset($c['uname']) ? $c['uname'] : '',
                    'product' => isset($c['product']) ? $c['product'] : '',
                    'warning_name' => isset($c['warning_name']) ? $c['warning_name'] : '',
                    'occur_time' => isset($c['occur_time']) ? $c['occur_time'] : date('Y-m-d H:i:s'),
                    'type' => $c['way'],
                    'message' => $c['message'],
                    'account' => isset($c['account']) ? $c['account'] : '',
                    'status' => $c['status'],
                    'times' => 1,
                    'show' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }
            if ($log) {
                WarningRepository::saveLog($log);
            }

            //清除缓存
            Cache::pull('warning_log_phone');
            Cache::pull('warning_log_email');
            Cache::pull('warning_log_ding');
            Cache::pull('warning_log_webhook');
            Cache::pull('warning_log_weixin');
        }
    }
}
