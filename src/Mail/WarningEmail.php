<?php

namespace Sy\Warning\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WarningEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $warning;

    /**
     * Create a new message instance.
     *
     * @return void
     * @param mixed $warning
     * @param mixed $params
     */
    public function __construct($params)
    {
        $this->warning = $params;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->warning->type == 1) {
            return $this->view('vendor.warning.scanEmail')->subject('聚合.云监测-风险信息提醒');
        }
        return $this->view('vendor.warning.threatEmail')->subject('聚合.云监测-风险信息提醒');
    }
}
