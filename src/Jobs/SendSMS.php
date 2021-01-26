<?php

namespace Sy\Warning\Jobs;

use Illuminate\Support\Facades\Http;

class SendSMS extends BaseJob
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->sendSmsCode();
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
    private function sendSmsCode()
    {
        dd(config("warning.WARNING_TYPE.PHONE"));
        $smsurl = 'https://v.juhe.cn/sms/send?key='.config('warning.SMSKey').'&mobile=' . $this->account . '&tpl_id='.config('warning.SMSTpl').'&tpl_value=' . urlencode($this->message);

        $params = $this->warningLog(config("warning.WARNING_TYPE.PHONE"));

        $content = Http::get($smsurl);
        $result = $content->json();

        if ($result) {
            $error_code = $result['error_code'];
            if ($error_code == 0) {
                $params['status'] = 1;
            } else {
                $params['status'] = 0;
            }
        } else {
            $params['status'] = 0;
        }

        $this->saveCache($params,config("warning.WARNING_TYPE.PHONE"));

    }
}
