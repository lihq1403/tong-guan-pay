<?php

namespace Lihq1403\TongGuanPay;

use Lihq1403\TongGuanPay\Exceptions\HttpException;
use Lihq1403\TongGuanPay\Exceptions\InvalidConfigException;
use Lihq1403\TongGuanPay\Supports\Config;
use Lihq1403\TongGuanPay\Supports\Sign;
use Lihq1403\TongGuanPay\Traits\HttpRequest;

class Pay
{
    use HttpRequest;

    protected $config;

    /**
     * 请求地址
     */
    protected static $host = [
        'dev' => 'aHR0cDovL3RnamYuODMzMDA2LmJpeg==',
        'normal' => 'aHR0cHM6Ly90Z3BheS44MzMwMDYubmV0'
    ];

    /**
     * 相关接口
     */
    protected static $api_route = [
        'qrCodePay' => 'L3RnUG9zcC9zZXJ2aWNlcy9wYXlBcGkvYWxsUXJjb2RlUGF5',
        'orderQuery' => 'L3RnUG9zcC9zZXJ2aWNlcy9wYXlBcGkvb3JkZXJRdWVyeQ==',
        'reverse' => 'L3RnUG9zcC9zZXJ2aWNlcy9wYXlBcGkvcmV2ZXJzZQ==',
    ];

    /**
     * Pay constructor.
     * @param array $config
     * @throws InvalidConfigException
     */
    public function __construct(array $config)
    {
        $this->config = new Config($config);
        if (empty($this->config->get('account'))) {
            throw new InvalidConfigException('Missing pay Config -- [account]');
        }
        if (empty($this->config->get('key'))) {
            throw new InvalidConfigException('Missing pay Config -- [key]');
        }

        // 解码 - 别问我为什么!!!
        self::$host['dev'] = base64_decode(self::$host['dev']);
        self::$host['normal'] = base64_decode(self::$host['normal']);
        self::$api_route['qrCodePay'] = base64_decode(self::$api_route['qrCodePay']);
        self::$api_route['orderQuery'] = base64_decode(self::$api_route['orderQuery']);
        self::$api_route['reverse'] = base64_decode(self::$api_route['reverse']);
    }

    /**
     * 一码付
     * @param $payMoney | 支付金额
     * @param $lowOrderId | 订单号
     * @param $notifyUrl | 回调地址
     * @param string $returnUrl | 页面成功跳转地址
     * @param string $body | 商品描述
     * @param string $attach | 描述字符串，最大50字节
     * @return array
     * @throws HttpException
     */
    public function qr($payMoney, $lowOrderId, $notifyUrl, $returnUrl = '', $body = '', $attach = '')
    {
        $curl_data = [
            'account' => $this->config->get('account'),
            'payMoney' => $payMoney,
            'lowOrderId' => $lowOrderId,
            'body' => $body,
            'attach' => $attach,
            'notifyUrl' => $notifyUrl,
            'returnUrl' => $returnUrl,
        ];
        $curl_data['sign'] = Sign::create($curl_data, $this->config->get('key'));

        try {
            $response = $this->post(self::$host[$this->config->get('mode', 'dev')].self::$api_route['qrCodePay'], [], ['json' => $curl_data]);
            if (empty($response['status']) || $response['status'] !== 100 || empty($response['codeUrl']) || empty($response['orderId'])) {
                throw new HttpException('返回异常：'.$response['message'] ?? '未知错误');
            }
            return [
                'codeUrl' => $response['codeUrl'],
                'lowOrderId' => $lowOrderId,
                'orderId' => $response['orderId'],
            ];
        } catch (\Exception $e) {
            throw new HttpException($e->getMessage());
        }
    }

    /**
     * 订单查询
     * @param $lowOrderId | 订单号
     * @return mixed|string
     * @throws HttpException
     */
    public function find($lowOrderId)
    {
        $curl_data = [
            'account' => $this->config->get('account'),
            'lowOrderId' => $lowOrderId,
        ];
        $curl_data['sign'] = Sign::create($curl_data, $this->config->get('key'));
        try {
            $response = $this->post(self::$host[$this->config->get('mode', 'dev')].self::$api_route['orderQuery'], [], ['json' => $curl_data]);
            if (empty($response['status']) || $response['status'] !== 100) {
                throw new HttpException('返回异常：'.$response['message'] ?? '未知错误');
            }
            // 0成功 1失败 2已撤销 4待支付 5已退款 6部分退款
            return $response;
        } catch (\Exception $e) {
            throw new HttpException($e->getMessage());
        }
    }

    /**
     * 退款
     * @param $lowOrderId
     * @param $upOrderId
     * @return mixed|string
     * @throws HttpException
     */
    public function reverse($lowOrderId, $upOrderId)
    {
        $curl_data = [
            'account' => $this->config->get('account'),
            'lowOrderId' => $lowOrderId,
            'upOrderId' => $upOrderId,
        ];
        $curl_data['sign'] = Sign::create($curl_data, $this->config->get('key'));
        try {
            $response = $this->post(self::$host[$this->config->get('mode', 'dev')].self::$api_route['reverse'], [], ['json' => $curl_data]);
            if (empty($response['status']) || $response['status'] !== 100) {
                throw new HttpException('返回异常：'.$response['message'] ?? '未知错误');
            }
            return $response;
        } catch (\Exception $e) {
            throw new HttpException($e->getMessage());
        }
    }
}