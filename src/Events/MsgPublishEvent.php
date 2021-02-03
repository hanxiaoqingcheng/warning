<?php

namespace Sy\Warning\Events;


class MsgPublishEvent
{

    //预警产品，对应warning_tpls表里面的product字段
    public $product;

    //用户名称
    public $username;

    //关键字，对应warning_tpls表里面warning_tpl字段里面##里面的值，用#keyword#=value的格式
    public $keyword;

    //用户或用户组id
    public $userId;

    //预警名称
    public $warningName;

    //自定义模板,等于1表示$keyword字段为自定义内容，无需去模板库转换
    public $custom;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        $keyword,
        $custom = 1,
        $product = 'default',
        $user_id = 1,
        $username = '',
        $warning_name = ''
    ) {
        $this->product = $product;
        $this->keyword = $keyword;
        $this->username = $username;
        $this->userId = $user_id;
        $this->warningName = $warning_name;
        $this->custom = $custom;
    }


}
