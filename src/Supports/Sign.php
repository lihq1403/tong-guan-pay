<?php

namespace Lihq1403\TongGuanPay\Supports;

class Sign
{
    public static function create($params, $key)
    {
        // 去空
        $params = array_filter($params,function ($var) {
            if($var === '' || $var === null)
            {
                return false;
            }
            return true;
        });
        // 字典升序
        $sign = self::formatParaMap($params);
        // 拼接key
        $sign = $sign.'&key='.$key;
        // md5
        $sign = md5($sign);
        // 转大写
        $sign = strtoupper($sign);
        return $sign;
    }

    private static function formatParaMap($paraMap)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v)
        {
            $buff .= $k . "=" . $v . "&";
        }
        $reqPar = '';  //去掉最后一个字符 $
        if (strlen($buff) > 0)
        {
            $reqPar = substr($buff, 0, strlen($buff)-1);
        }
        return $reqPar;
    }
}