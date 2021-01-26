<?php

namespace Sy\Warning\Jobs;

use Illuminate\Support\Facades\Http;

class SendWebhook extends BaseJob
{


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->webhookSend();
    }


    private function webhookSend()
    {
        $params = $this->warningLog(config("warning.WARNING_TYPE.WEBHOOK"));

        $postData = [
            'msg' => $this->message
        ];
        try {
            $content = Http::post($this->account, $postData);
            $result = $content->json();

            if (isset($result['code'])) {
                $error_code = $result['code'];
                if ($error_code == 0) {
                    $params['status'] = 1;
                } else {
                    $params['status'] = 0;
                }
            } else {
                $params['status'] = 0;
            }

            $this->saveCache($params, config("warning.WARNING_TYPE.WEBHOOK"));
        } catch (\Exception $e) {
            msgExport(1002);
        }
    }
}
