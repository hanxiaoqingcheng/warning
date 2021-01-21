<?php

namespace Sy\Warning\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class SendWeixin implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $params;

    public $account;

    /**
     * Create a new job instance.
     *
     * @return void
     * @param mixed $account
     * @param mixed $params
     * @param mixed $uname
     */
    public function __construct($account, $params, $uname)
    {
        $this->params = $params;
        $this->params->uname = $uname;
        $this->account = $account;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->sendWeixin($this->account, $this->params);
    }

    private function sendWeixin($ding, $params)
    {
        $params->way = 'weixin';
        $params->message = 'count=' . $params->count . '&keywords=' . $params->keyword;
        $params->account = $ding;
        $params->times = 1;
        $params->occur_time = date('Y-m-d H:i:s');

        if ($params->type == 1) {
            $content = '您扫描的关键字「' . $params->keyword . '」，有 ' . $params->count . ' 个新增未知风险待确认，请您前往 https://scan.juhe.cn/ 查看。';
        } else {
            $content = '您监控的关键字「' . $params->keyword . '」，有 ' . $params->count . ' 个新增威胁情报，请您前往 https://scan.juhe.cn/ 查看。';
        }

        $postData = [
            'msgtype' => 'text',
            'text' => [
                'content' => $content
            ]
        ];
        $content = Http::post($ding, $postData);
        $result = $content->json();

        if ($result && isset($result['errcode'])) {
            $error_code = $result['errcode'];
            if ($error_code == 0) {
                $params->status = 1;
            } else {
                $params->status = 0;
            }
        } else {
            $params->status = 0;
        }
        $data = json_decode(json_encode($params), 1);


        $weixinCache[] = $data;
        $cache = Cache::get('warning_log_weixin');

        if ($cache) {
            $cache = json_decode($cache, 1);

            $weixinCache = array_merge($weixinCache, $cache);
        }

        Cache::put('warning_log_weixin', json_encode($weixinCache));
    }
}
