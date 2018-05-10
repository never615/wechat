<?php

namespace EasyWeChat\OfficialAccount\Card;

/**
 * Created by PhpStorm.
 * User: never615 <never615.com>
 * Date: 2018/5/10
 * Time: 下午3:19
 */
class InvoiceClient extends \EasyWeChat\Kernel\BaseClient
{
    /**
     * 查询授权完成状态
     *
     * @param $orderId
     * @param $s_pappid
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     */
    public function getAuthData($orderId, $s_pappid)
    {
        $params = [
            'order_id' => $orderId,
            's_pappid' => $s_pappid,
        ];

        return $this->httpPostJson('card/invoice/getauthdata', $params);
    }


    /**
     * 解码code
     *
     * @param $encryptCode
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     */
    public function decryptCode($encryptCode)
    {
        $params = [
            'encrypt_code' => $encryptCode,
        ];

        return $this->httpPostJson('card/code/decrypt', $params);
    }


    /**
     * 拒绝开票
     *
     * @param      $s_pappid
     * @param      $orderId
     * @param      $reason
     * @param null $url
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     */
    public function reject($s_pappid, $orderId, $reason, $url = null)
    {
        $params = [
            "s_pappid" => $s_pappid,
            "order_id" => $orderId,
            "reason"   => $reason,
            'url'      => $url,
        ];

        return $this->httpPostJson('card/invoice/rejectinsert', $params);
    }


    /**
     * 创建发票卡券
     *
     * @param       $payee
     * @param       $type
     * @param array $baseInfo
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     */
    public function create(
        $payee,
        $type,
        array $baseInfo = []
    ) {
        $params = [
            'invoice_info' => [
                'base_info' => $baseInfo,
                'payee'     => $payee,
                'type'      => $type,
            ],
        ];

        return $this->httpPostJson('card/invoice/platform/createcard', $params);
    }


    /**
     * 取授权页链接
     *
     * @param        $ticket
     * @param        $s_pappid ,开票平台在微信的标识号，商户需要找开票平台提供
     * @param        $orderId  ,订单id，在商户内单笔开票请求的唯一识别号，
     * @param        $money
     * @param        $timestamp
     * @param null   $redirectUrl
     * @param int    $type
     * @param string $source
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     */
    public function getAuthUrl(
        $ticket,
        $s_pappid,
        $orderId,
        $money,
        $timestamp,
        $redirectUrl = null,
        $type = 0,
        $source = 'web'
    ) {

        $params = [
            's_pappid'     => $s_pappid,
            'order_id'     => $orderId,
            'money'        => $money,
            'timestamp'    => $timestamp,
            'source'       => $source,
            'redirect_url' => $redirectUrl,
            'ticket'       => $ticket,
            'type'         => $type,
        ];

        return $this->httpPostJson('card/invoice/getauthurl', $params);
    }


    public function setBizAttr($phone, $timeOut)
    {
        $params = [
            'contact' => [
                "phone"    => $phone,
                "time_out" => $timeOut,
            ],
        ];

        return $this->httpPostJson('card/invoice/setbizattr', $params, [
            "action" => "set_contact",
        ]);
    }

//
//    /**
//     * //todo
//     * 更改发票信息接口 and 设置跟随推荐接口.
//     *
//     * @param       $cardId
//     * @param array $baseInfo
//     * @param array $especial
//     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
//     */
//    public function update($cardId, $baseInfo = [], $especial = [])
//    {
//        $card = [];
//        $card['card_id'] = $cardId;
//        $card['invoice_info'] = [];
//
//        $cardInfo = [];
//        if ($baseInfo) {
//            $cardInfo['base_info'] = $baseInfo;
//        }
//
//        $card['invoice_info'] = array_merge($cardInfo, $especial);
//
//        return $this->httpPostJson('card/update', $card);
//    }


}
