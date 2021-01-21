<?php

namespace Sy\Warning\Events;

use Sy\Warning\MsgEntity;

class MsgPublishEvent
{

    public $msgEntity;

    /**
     * Create a new event instance.
     *
     * @return void
     * @param MsgEntity $msgEntity
     */
    public function __construct(MsgEntity $msgEntity)
    {
        $this->msgEntity = $msgEntity;
    }

}
