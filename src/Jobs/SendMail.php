<?php

namespace Sy\Warning\Jobs;

use Illuminate\Support\Facades\Mail;

class SendMail extends BaseJob
{

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $params = $this->warningLog(config("warning.WARNING_TYPE.EMAIL"));
        try {
            Mail::raw($params['message'], function ($message) {
                $to = $this->account;
                $message->to($to)->subject(config("warning.WarningSUB"));
            });
            $params['status'] = 1;
        } catch (\Exception $e) {
            $params['status'] = 0;
        }
        $this->saveCache($params, config("warning.WARNING_TYPE.EMAIL"));
    }
}
