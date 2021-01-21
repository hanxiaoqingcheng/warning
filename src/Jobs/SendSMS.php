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

class SendSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $params;

    public $account;

    public $url = 'https://scan.juhe.cn/';

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
        $this->sendSmsCode($this->account, $this->params);
    }

    /**
     *
     * 发送短信
     * 短信发送不根据type，统一的模板
     * @param $mobile
     * @param $code
     * @param mixed $params
     * @return array|string
     */
    private function sendSmsCode($mobile, $params)
    {
        $tplvalue = '#num#=' . $params->count."&#url#=$this->url";
        $smsurl = 'https://v.juhe.cn/sms/send?key='.config('SMSKey').'&mobile=' . $mobile . '&tpl_id='.config('SMSTpl').'&tpl_value=' . urlencode($tplvalue);

        $params->way = 'phone';
        $params->message = $tplvalue;
        $params->account = $mobile;
        $params->times = 1;
        $params->occur_time = date('Y-m-d H:i:s');


        $content = Http::get($smsurl);
        $result = $content->json();
        if ($result) {
            $error_code = $result['error_code'];
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
            $phoneCache[] = $data;
            $cache = Cache::get('warning_log_phone');

            if ($cache) {
                $cache = json_decode($cache, 1);

                $phoneCache = array_merge($phoneCache, $cache);
            }

            Cache::put('warning_log_phone', json_encode($phoneCache));
        } catch (Exception $e) {
            msgExport(1002);
        }
    }
}
