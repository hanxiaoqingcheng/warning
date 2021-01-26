<?php

namespace Sy\Warning;

use Sy\Warning\Models\WarningLog;
use Sy\Warning\Models\WarningUserSet;
use Exception;
use Sy\Warning\Models\WarningTpl;

class WarningRepository
{
    /**
     * 插入用户预警地址,账号+",1"表示显示，0表示不显示
     * @param array $params
     * @return WarningUserSet|bool
     */
    public static function setUserWarningAddress($params = [])
    {
        $params['uname'] = isset($params['uname']) ? $params['uname'] : '';

        $type = '';
        $account = '';
        if (isset($params['email'])) {
            $type = 'email';
            $account = $params['email'];
        }
        if (isset($params['phone'])) {
            $type = 'phone';
            $account = $params['phone'];
        }
        if (isset($params['ding'])) {
            $type = 'ding';
            $account = $params['ding'];
        }
        if (isset($params['webhook'])) {
            $type = 'webhook';
            $account = $params['webhook'];
        }
        if (isset($params['weixin'])) {
            $type = 'weixin';
            $account = $params['weixin'];
        }

        if ($type) {
            $userSet = WarningUserSet::firstOrNew([
                'uid' => $params['uid'],
                'uname' => $params['uname'],
                'type' => $type
            ]);
            $userSet->uid = $params['uid'];
            if (isset($params['uname'])) {
                $userSet->uname = $params['uname'];
            }
            $userSet->type = $type;
            $userSet->account = $account;

            try {
                $userSet->save();
            } catch (Exception $e) {
                return false;
            }
            return $userSet;
        }
        return false;
    }

    /**
     * 根据用户id获取全部设置的账号
     *
     * uname的作用，可以把id当成用户组，用户组下面有不同的负责人，设置不同的账号，发送不同的模板
     * @param $uid
     * @return mixed
     */
    public static function getUserAccount($uid, $uname = '')
    {
        return WarningUserSet::where('uid', $uid)
            ->select('type', 'account', 'show', 'uname')
            ->when($uname, function ($item) use ($uname) {
                $item->where('uname', $uname);
            })
            ->get();
    }

    /**
     * 获取用户发送账号
     *
     * uname的作用，可以把id当成用户组，用户组下面有不同的负责人，设置不同的账号，发送不同的模板
     *
     * @param $uid
     * @param string $uname
     * @return mixed
     */
    public static function getShowAccount($uid, $uname = '')
    {
        return WarningUserSet::where('uid', $uid)
            ->when($uname, function ($item) use ($uname) {
                $item->where('uname', $uname);
            })
            ->where('show',1)
            ->pluck('account','type');
    }


    /**
     * 修改账号显示和不显示(1,显示，-1不显示)
     * @param $uid
     * @param $account
     * @param $show
     * @param string $uname
     * @param mixed $type
     */
    public static function showAccount($uid, $type, $show, $uname = '')
    {
        try {
            WarningUserSet::where('uid', $uid)
                ->when($uname, function ($item) use ($uname) {
                    $item->where('uname', $uname);
                })
                ->where('type', $type)
                ->update(['show' => $show]);
        } catch (Exception $e) {
            msgExport(1002);
        }
    }

    /**
     * 保存发送日志
     * @param $params
     */
    public static function saveLog($params)
    {
        try {
            WarningLog::insert($params);
        } catch (Exception $e) {
            msgExport(1002);
        }
    }

    /**
     * 根据用户名和产品获取需要发送的模板
     *
     * @param $uid
     * @param $product
     * @param string $uname
     * @return mixed
     */
    public static function getWarningTpl($uid, $product,$uname = '',$warningName = '')
    {
        return WarningTpl::where('show', 1)
            ->where('uid', $uid)
            ->where('product', $product)
            ->when($uname, function ($item) use ($uname){
                $item->where('uname', $uname);
            })
            ->when($warningName, function ($item) use ($warningName) {
                $item->where('warning_name', $warningName);
            })
            ->pluck('warning_tpl','type');
    }
}
