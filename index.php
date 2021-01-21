<?php

require __DIR__ . '/vendor/autoload.php';

new Sy\Warning\MsgEntity(2, '聚合数据', 3, 6);

event(new MsgPublishEvent($msg));