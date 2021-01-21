<?php

namespace Sy\Warning\Events;

use App\Jobs\SendDing;
use App\Jobs\SendMail;
use App\Jobs\SendSMS;
use App\Jobs\SendWebhook;
use App\Jobs\SendWeixin;
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
                    SendMail::dispatch($user->account, $params, $user->uname);
                }
                if ($user->type == 'phone') {
                    SendSMS::dispatch($user->account, $params, $user->uname);
                }
                if ($user->type == 'ding') {
                    SendDing::dispatch($user->account, $params, $user->uname);
                }
                if ($user->type == 'webhook') {
                    SendWebhook::dispatch($user->account, $params, $user->uname);
                }
                if ($user->type == 'weixin') {
                    SendWeixin::dispatch($user->account, $params, $user->uname);
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
