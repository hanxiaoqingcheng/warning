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
    public function __construct($warning, $params)
    {
        foreach ($warning as $user) {
            if ($user->show == 1) {
                if ($user->type == 'email') {
                    SendMail::dispatch($user->account, $params, $user->uname)->onConnection('redis');
                }
                if ($user->type == 'phone') {
                    SendSMS::dispatch($user->account, $params, $user->uname)->onConnection('redis');
                }
                if ($user->type == 'ding') {
                    SendDing::dispatch($user->account, $params, $user->uname)->onConnection('redis');
                }
                if ($user->type == 'webhook') {
                    SendWebhook::dispatch($user->account, $params, $user->uname)->onConnection('redis');
                }
                if ($user->type == 'weixin') {
                    SendWeixin::dispatch($user->account, $params, $user->uname)->onConnection('redis');
                }
            }
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
