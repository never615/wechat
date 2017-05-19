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
 * SuiteAccessToken.php.
 *
 * Part of Overtrue\WeChat.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author    never615 <never615@gmail.com>
 * @copyright 2017
 *
 * @see      https://github.com/overtrue
 * @see      http://overtrue.me
 */

namespace EasyWeChat\CorpServer\Core;

use EasyWeChat\Foundation\Core\AccessToken as BaseAccessToken;

class SuiteAccessToken extends BaseAccessToken
{
    /**
     * Ticket.
     *
     * @var \EasyWeChat\CorpServer\Core\Ticket
     */
    protected $ticket;

    /**
     * API.
     */
    const API_TOKEN_GET = 'https://api.weixin.qq.com/cgi-bin/component/api_component_token';

    /**
     * {@inheritdoc}.
     */
    protected $queryName = 'component_access_token';

    /**
     * {@inheritdoc}.
     */
    protected $tokenJsonKey = 'component_access_token';

    /**
     * {@inheritdoc}.
     */
    protected $prefix = 'easywechat.open_platform.component_access_token.';

    /**
     * Set VerifyTicket.
     *
     * @param \EasyWeChat\CorpServer\Core\Ticket $ticket
     *
     * @return $this
     */
    public function setTicket(Ticket $ticket)
    {
        $this->ticket = $ticket;

        return $this;
    }

    /**
     * {@inheritdoc}.
     */
    public function requestFields(): array
    {
        return [
            'component_appid' => $this->getClientId(),
            'component_appsecret' => $this->getClientSecret(),
            'component_verify_ticket' => $this->ticket->getTicket(),
        ];
    }
}
