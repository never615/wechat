<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * CorpServer.php.
 *
 * Part of Overtrue\WeChat.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author    never615 <never615@gmail.com>
 * @copyright 2017
 *
 * @see       https://github.com/overtrue
 * @see       http://overtrue.me
 */

namespace EasyWeChat\CorpServer\Broadcast;

use EasyWeChat\Exceptions\HttpException;
use EasyWeChat\Foundation\Core\AbstractAPI;

/**
 * Class Broadcast.
 */
class Broadcast extends AbstractAPI
{
    const API_SEND_MESSAGE = "https://qyapi.weixin.qq.com/cgi-bin/message/send";

    const MSG_TYPE_TEXT = 'text'; // 文本
//    const MSG_TYPE_NEWS = 'news'; // 图文
//    const MSG_TYPE_VOICE = 'voice'; // 语音
//    const MSG_TYPE_IMAGE = 'image'; // 图片
//    const MSG_TYPE_VIDEO = 'video'; // 视频
//    const MSG_TYPE_CARD = 'card'; // 卡券

    const SEND_TO_USER = "touser";    //给指定用户发送
    const SEND_TO_PARTY = "toparty";  //给指定部门发送
    const SEND_TO_TAG = "totag";      //给指定标签发送

    /**
     * Send a message.
     *
     * @param string $msgType message type
     * @param mixed  $message message
     * @param mixed  $to
     * @param string $agentId
     * @param string $by
     * @return mixed
     * @throws \EasyWeChat\Exceptions\InvalidArgumentException
     */
    public function send($msgType, $message, $to = null, $agentId, $by = self::SEND_TO_USER)
    {
        $message = (new MessageBuilder())
            ->agentId($agentId)
            ->msgType($msgType)
            ->message($message)
            ->to($to)
            ->by($by)
            ->build();

        $result= $this->post(self::API_SEND_MESSAGE, $message);
        return $result;


    }

    /**
     * Send a text message.
     *
     * @param mixed  $message message
     * @param mixed  $to
     *
     * @param        $agentId
     * @param string $by
     * @return mixed
     */
    public function sendText($message, $to = null, $agentId, $by = self::SEND_TO_USER)
    {
        return $this->send(self::MSG_TYPE_TEXT, $message, $to, $agentId, $by);
    }
//
//    /**
//     * Send a news message.
//     *
//     * @param mixed $message message
//     * @param mixed $to
//     *
//     * @return mixed
//     */
//    public function sendNews($message, $to = null)
//    {
//        return $this->send(self::MSG_TYPE_NEWS, $message, $to);
//    }
//
//    /**
//     * Send a voice message.
//     *
//     * @param mixed $message message
//     * @param mixed $to
//     *
//     * @return mixed
//     */
//    public function sendVoice($message, $to = null)
//    {
//        return $this->send(self::MSG_TYPE_VOICE, $message, $to);
//    }
//
//    /**
//     * Send a image message.
//     *
//     * @param mixed $message message
//     * @param mixed $to
//     *
//     * @return mixed
//     */
//    public function sendImage($message, $to = null)
//    {
//        return $this->send(self::MSG_TYPE_IMAGE, $message, $to);
//    }
//
//    /**
//     * Send a video message.
//     *
//     * @param mixed $message message
//     * @param mixed $to
//     *
//     * @return mixed
//     */
//    public function sendVideo($message, $to = null)
//    {
//        return $this->send(self::MSG_TYPE_VIDEO, $message, $to);
//    }
//
//    /**
//     * Send a card message.
//     *
//     * @param mixed $message message
//     * @param mixed $to
//     *
//     * @return mixed
//     */
//    public function sendCard($message, $to = null)
//    {
//        return $this->send(self::MSG_TYPE_CARD, $message, $to);
//    }


    /**
     * post request.
     *
     * @param string       $url
     * @param array|string $options
     *
     * @return \EasyWeChat\Support\Collection
     *
     * @throws HttpException
     */
    private function post($url, $options)
    {
        return $this->parseJSON('json', [$url, $options]);
    }
}
