<?php

namespace Sy\Warning\Jobs;

use Illuminate\Support\Facades\Http;

class SendWeixin extends BaseJob
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->sendWeixin();
    }

    private function sendWeixin()
    {

        $params = $this->warningLog(config("warning.WARNING_TYPE.WEIXIN"));

        $postData = [
            'msgtype' => 'text',
            'text' => [
                'content' => $this->message
            ]
        ];
        $content = Http::post($this->account, $postData);
        $result = $content->json();

        if ($result && isset($result['errcode'])) {
            $error_code = $result['errcode'];
            if ($error_code == 0) {
                $params['status'] = 1;
            } else {
                $params['status'] = 0;
            }
        } else {
            $params['status'] = 0;
        }

        $this->saveCache($params,config("warning.WARNING_TYPE.WEIXIN"));
    }
}
