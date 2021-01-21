<?php

namespace Sy\Warning\Listeners;

use Illuminate\Contracts\Events\Dispatcher;
use Sy\Warning\Events\MsgPublishEvent;
use Sy\Warning\Events\WarningSendEvent;
use Sy\Warning\WarningRepository;

class MsgPublishListener
{
    public $connection = 'redis';

    public $events;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Dispatcher $events)
    {
        $this->events = $events;
    }

    /**
     * Handle the event.
     *
     * @param MsgPublishEvent $event
     * @return void
     */
    public function handle(MsgPublishEvent $event)
    {
        $uid = $event->msgEntity->user_id;

        //查询数据库
        $accountInfo = WarningRepository::getUserAccount($uid);

        if ($accountInfo) {
            $this->events->dispatch(new WarningSendEvent($accountInfo, $event->msgEntity));
        }
    }
}
