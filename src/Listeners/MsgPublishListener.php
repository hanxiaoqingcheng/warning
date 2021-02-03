<?php

namespace Sy\Warning\Listeners;

use Illuminate\Contracts\Events\Dispatcher;
use Sy\Warning\Events\MsgPublishEvent;
use Sy\Warning\Events\WarningSendEvent;
use Sy\Warning\WarningRepository;

class MsgPublishListener
{
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
        //查询数据库,获取需要发送的账号
        $accountInfo = WarningRepository::getShowAccount($event->userId, $event->username);

        $tpl = WarningRepository::getWarningTpl($event->userId, $event->product, $event->username, $event->warningName);

        //处理需要发送的账户和模板的对应关系
        if ($accountInfo) {
            foreach ($accountInfo as $type => $account) {
                if (isset($tpl[$type])) {
                    $sendData[$type] = [
                        'account' => $account,
                        'content' => $event->custom == 1 ? $event->keyword : $this->getContent($event->keyword,
                            $tpl[$type]),
                    ];
                } else {
                    if (isset($tpl['default'])) {
                        //可以配置默认模板
                        $sendData[$type] = [
                            'account' => $account,
                            'content' => $event->custom == 1 ? $event->keyword : $this->getContent($event->keyword,
                                $tpl['default']),
                        ];
                    } else {
                        if (config('warning.Warning_TPL')) {
                            //可以配置默认模板
                            $sendData[$type] = [
                                'account' => $account,
                                'content' => $event->custom == 1 ? $event->keyword : $this->getContent($event->keyword,
                                    config('warning.Warning_TPL')),
                            ];
                        }
                    }
                }
                if ($type == 'phone') {
                    $sendData['phone'] = [
                        'account' => $account,
                        'content' => $event->keyword
                    ];
                }

            }
            if (isset($sendData)) {
                $this->events->dispatch(new WarningSendEvent($sendData, $event));
            }

        }
    }


    /**
     * 获取组装之后的预警内容
     *
     * @param $tplValue
     * @param $tpl
     * @return mixed
     */
    public function getContent($tplValue, $tpl)
    {
        $tplValueArray = explode('&', $tplValue);
        foreach ($tplValueArray as $t) {
            preg_match('/^(#.*?#)=([\s\S]*?)$/', $t, $vo);
            if ($vo) {
                $tpl = str_replace($vo[1], $vo[2], $tpl);
            }
        }
        return $tpl;
    }
}
