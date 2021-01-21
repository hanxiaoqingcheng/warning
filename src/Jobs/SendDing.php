<?php

namespace Sy\Warning\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class SendDing implements ShouldQueue
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
        $this->sendDing($this->account, $this->params);
    }

    /**
     * 钉钉根据type发送不同的模板
     * @param $ding
     * @param $params
     */
    private function sendDing($ding, $params)
    {
        $params->way = 'ding';
        $params->message = 'count='.$params->count.'&keywords='.$params->keyword;
        $params->account = $ding;
        $params->times = 1;
        $params->occur_time = date('Y-m-d H:i:s');

        if ($params->type == 1) {
            $content = '您扫描的关键字「'.$params->keyword.'」，有 ' . $params->count . ' 个新增未知风险待确认，请您前往 https://scan.juhe.cn/ 查看。';
        } else {
            $content = '您监控的关键字「'.$params->keyword.'」，有 ' . $params->count . ' 个新增威胁情报，请您前往 https://scan.juhe.cn/ 查看。';
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

        try {
            $dingCache[] = $data;
            $cache = Cache::get('warning_log_ding');

            if ($cache) {
                $cache = json_decode($cache, 1);

                $dingCache = array_merge($dingCache, $cache);
            }

            Cache::put('warning_log_ding', json_encode($dingCache));
        } catch (Exception $e) {
            msgExport(1002);
        }
    }
}
