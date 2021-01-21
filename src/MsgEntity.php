<?php

namespace Sy\Warning;

class MsgEntity
{
    //1:代码扫描，2:威胁情报
    public $type;

    //关键字
    public $keyword;

    //风险或者情报的数量
    public $count;

    //用户id
    public $user_id;


    public function __construct($type, $keyword, $count, $user_id)
    {
        $this->type = $type;
        $this->keyword = $keyword;
        $this->count = $count;
        $this->user_id = $user_id;
    }
}
