<?php

namespace Sy\Warning\Jobs;

use Sy\Warning\Mail\WarningEmail;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class SendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $content;

    public $account;

    /**
     * Create a new job instance.
     *
     * @return void
     * @param array $accountAndTpl
     */
    public function __construct($accountAndTpl)
    {
        $this->content = $accountAndTpl['content'];
        $this->account = $accountAndTpl['account'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $params = $this->params;
        $params->way = 'mail';
        $params->message = $this->content;
        $params->times = 1;
        $params->occur_time = date('Y-m-d H:i:s');
        $params->account = $this->account;

        try {
            Mail::to($this->account)->send(new WarningEmail($this->params));
            $params->status = 1;
        } catch (Exception $e) {
            $params->status = 0;
        }
        $data = json_decode(json_encode($params), 1);

        try {
            $mailCache[] = $data;
            $cache = Cache::get('warning_log_mail');

            if ($cache) {
                $cache = json_decode($cache, 1);

                $mailCache = array_merge($mailCache, $cache);
            }

            Cache::put('warning_log_mail', json_encode($mailCache));
        } catch (Exception $e) {
            msgExport(1002);
        }
    }
}
