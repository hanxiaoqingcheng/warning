<?php
return [

    //支持的预警类型
    'WARNING_TYPE' => [
        'PHONE' => 'phone',
        'DING' => 'ding',
        'EMAIL' => 'email',
        'WEBHOOK' => 'webhook',
        'WEIXIN' => 'weixin'
    ],

    //发送短信聚合账号
    'SMSKey' => '',

    //发送短信聚合模板号
    'SMSTpl' => '',

    //默认预警模板
    'Warning_TPL' => '您扫描的关键字「#keyword#」，有#num#个新增未知风险待确认，请您前往#url#查看。'


];