<?php

require __DIR__.'/../vendor/autoload.php';

$config = [
    'account' => '',
    'key' => '',
    'mode' => ''
];

$pay = new \Lihq1403\TongGuanPay\Pay($config);

$payMoney = '0.01';
$lowOrderId = date('YmdHis');
$notify_url = '';

$response = $pay->qr($payMoney, $lowOrderId, $notify_url);
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

//$lowOrderId = '20190517095914';
//$response = $pay->find($lowOrderId);
//echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

//$lowOrderId = '20190517095914';
//$upOrderId = '91129204701658222592';
//$response = $pay->reverse($lowOrderId, $upOrderId);
//echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

//$payMoney = '0.01';
//$lowOrderId = date('YmdHis');
//$notify_url = '';
//$payType = 0;
//$body = '测试支付';
//
//$response = $pay->qrPay($payMoney, $lowOrderId, $notify_url, $payType, $body);
//echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);