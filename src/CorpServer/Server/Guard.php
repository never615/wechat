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
 * Guard.php.
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

namespace EasyWeChat\CorpServer\Server;

use EasyWeChat\Exceptions\RuntimeException;
use EasyWeChat\OfficialAccount\Server\BadRequestException;
use EasyWeChat\OfficialAccount\Server\Guard as ServerGuard;
use EasyWeChat\Support\Collection;
use EasyWeChat\Support\Log;
use Symfony\Component\HttpFoundation\Response;

class Guard extends ServerGuard
{
    const EVENT_CREATE_AUTH = 'create_auth';
    const EVENT_CANCEL_AUTH = 'cancel_auth';
    const EVENT_CHANGE_AUTH = 'change_auth';
    const EVENT_SUITE_TICKET = 'suite_ticket';

    /**
     * Event handlers.
     *
     * @var \EasyWeChat\Support\Collection
     */
    protected $handlers;

    /**
     * Set handlers.
     *
     * @param array $handlers
     * @return $this
     */
    public function setHandlers(array $handlers)
    {
        $this->handlers = new Collection($handlers);

        return $this;
    }

    /**
     * Get handlers.
     *
     * @return \EasyWeChat\Support\Collection
     */
    public function getHandlers()
    {
        return $this->handlers;
    }

    /**
     * Get handler.
     *
     * @param string $type
     *
     * @return \EasyWeChat\OpenPlatform\EventHandlers\EventHandler|null
     */
    public function getHandler($type)
    {
        return $this->handlers->get($type);
    }

    /**
     * {@inheritdoc}
     */
    public function serve()
    {
        if ($str = $this->request->get('echostr')) {
            return new Response($this->getMessage(true));
        }

        $message = $this->getMessage();

        // Handle Messages.
        if (isset($message['MsgType'])) {
            return parent::serve();
        }

        Log::debug('Corp server Request received:', [
            'Method'   => $this->request->getMethod(),
            'URI'      => $this->request->getRequestUri(),
            'Query'    => $this->request->getQueryString(),
            'Protocal' => $this->request->server->get('SERVER_PROTOCOL'),
            'Content'  => $this->request->getContent(),
        ]);

        // If sees the `auth_code` query parameter in the url, that is,
        // authorization is successful and it calls back, meanwhile, an
        // `authorized` event, which also includes the auth code, is sent
        // from WeChat, and that event will be handled.
        if ($this->request->get('auth_code')) {
            return new Response(self::SUCCESS_EMPTY_RESPONSE);
        }

        $this->handleEventMessage($message);

        return new Response(self::SUCCESS_EMPTY_RESPONSE);
    }


    /**
     * Get request message.
     *
     * @param bool $verify if verify echostr
     * @return array
     * @throws BadRequestException
     * @throws RuntimeException
     */
    public function getMessage($verify = false)
    {
        $message = $this->parseMessageFromRequest($verify);

        if ((!is_array($message) && !$verify) || empty($message)) {
            throw new BadRequestException('Invalid request.');
        }


        return $message;
    }

    /**
     * Parse message array from raw php input.
     *
     * @param bool|resource|string $verify
     * @return array
     * @throws RuntimeException
     */
    protected function parseMessageFromRequest($verify=false)
    {
        if ($verify) {
            $content = $this->request->get('echostr');
        } else {
            $content = $this->request->getContent(false);
        }

        if (!$this->encryptor) {
            throw new RuntimeException('Safe mode Encryptor is necessary, please use Guard::setEncryptor(Encryptor $encryptor) set the encryptor instance.');
        }

        $message = $this->encryptor->decryptMsg(
            $this->request->get('msg_signature'),
            $this->request->get('nonce'),
            $this->request->get('timestamp'),
            $content,
            $verify
        );

        return $message;
    }


    /**
     * Handle event message.
     *
     * @param array $message
     */
    protected function handleEventMessage(array $message)
    {
        Log::debug('CorpServer Event Message detail:', $message);

        $message = new Collection($message);

        $infoType = $message->get('InfoType');

        if ($handler = $this->getHandler($infoType)) {
            $handler->handle($message);
        } else {
            Log::notice("No existing handler for '{$infoType}'.");
        }

        if ($messageHandler = $this->getMessageHandler()) {
            call_user_func_array($messageHandler, [$message]);
        }
    }
}
