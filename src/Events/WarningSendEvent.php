<?php

namespace Sy\Warning\Events;

use Sy\Warning\Jobs\SendDing;
use Sy\Warning\Jobs\SendMail;
use Sy\Warning\Jobs\SendSMS;
use Sy\Warning\Jobs\SendWebhook;
use Sy\Warning\Jobs\SendWeixin;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WarningSendEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     * @param mixed $warning
     * @param mixed $params
     */
    public function __construct($warning,$event)
    {

        foreach ($warning as $type=>$tplAndAccount) {
            if ($type == 'email') {
                SendMail::dispatch($tplAndAccount,$event);
            }
            // if ($type == 'phone') {
            //     SendSMS::dispatch($tplAndAccount,$event);
            // }
            // if ($type == 'ding') {
            //     SendDing::dispatch($tplAndAccount,$event);
            // }
            // if ($type == 'webhook') {
            //     SendWebhook::dispatch($tplAndAccount,$event);
            // }
            // if ($type == 'weixin') {
            //     SendWeixin::dispatch($tplAndAccount,$event);
            // }
        }

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
