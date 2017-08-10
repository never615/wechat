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

use EasyWeChat\Exceptions\InvalidArgumentException;
use EasyWeChat\Exceptions\RuntimeException;

/**
 * Class MessageBuilder.
 */
class MessageBuilder
{
    /**
     * Message target user or group.
     *
     * @var mixed
     */
    protected $to;

    protected $agentId;
    protected $by;

    /**
     * Message type.
     *
     * @var string
     */
    protected $msgType;

    /**
     * Message.
     *
     * @var mixed
     */
    protected $message;

    /**
     * Message types.
     *
     * @var array
     */
    private $msgTypes = [
        Broadcast::MSG_TYPE_TEXT,
        Broadcast::MSG_TYPE_TEXT_CARD,
//        Broadcast::MSG_TYPE_NEWS,
//        Broadcast::MSG_TYPE_IMAGE,
//        Broadcast::MSG_TYPE_VIDEO,
//        Broadcast::MSG_TYPE_VOICE,
//        Broadcast::MSG_TYPE_CARD,
    ];

    /**
     * Set message type.
     *
     * @param string $msgType
     *
     * @return MessageBuilder
     *
     * @throws InvalidArgumentException
     */
    public function msgType($msgType)
    {
        if (!in_array($msgType, $this->msgTypes, true)) {
            throw new InvalidArgumentException('This message type not exist.');
        }

        $this->msgType = $msgType;

        return $this;
    }

    /**
     * Set message.
     *
     * @param string|array $message
     *
     * @return MessageBuilder
     */
    public function message($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Set target user or group.
     *
     * @param mixed $to
     *
     * @return MessageBuilder
     */
    public function to($to)
    {
        $this->to = $to;

        return $this;
    }

    public function agentId($agentId)
    {
        $this->agentId = $agentId;

        return $this;
    }

    public function by($by)
    {
        $this->by = $by;

        return $this;
    }

    /**
     * Build message.
     *
     * @return bool
     *
     * @throws RuntimeException
     */
    public function build()
    {
        if (empty($this->msgType)) {
            throw new RuntimeException('message type not exist.');
        }

        if (empty($this->message)) {
            throw new RuntimeException('No message content to send.');
        }

        if (empty($this->by)) {
            throw new RuntimeException('by not exist.');
        }

        if (empty($this->to)) {
            throw new RuntimeException('to not exist.');
        }

        if (empty($this->agentId)) {
            throw new RuntimeException('agentId not exist.');
        }


//        // 群发视频消息给用户列表时，视频消息格式需要另外处理，具体见文档
//        if (isset($this->to) && is_array($this->to) && $this->msgType === Broadcast::MSG_TYPE_VIDEO) {
//            $this->msgType = 'video';
//        } elseif ($this->msgType === Broadcast::MSG_TYPE_VIDEO) {
//            $this->msgType = 'mpvideo';
//        }

        $content = (new Transformer($this->msgType, $this->message))->transform();


        $to = $this->to;
        if (is_array($to)) {
            $to = implode("|", $to);
        } else {
            if ($to === "all") {
                $to = "@all";
            }
        }

        $message = array_merge([
            "agentid" => $this->agentId,
            $this->by => $to,
        ], $content);

        return $message;
    }

    /**
     * Return property.
     *
     * @param string $property
     *
     * @return mixed
     */
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }


}
