<?php

namespace Sy\Warning;


trait EventMap
{
    /**
     * All of the Horizon event / listener mappings.
     *
     * @var array
     */
    protected $events = [
        Events\MsgPublishEvent::class => [
            Listeners\MsgPublishListener::class,
        ],
        Events\WarningSendEvent::class => [
            Listeners\WarningSendListener::class,
        ]
    ];
}
