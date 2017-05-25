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
 * Js.php.
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

namespace EasyWeChat\CorpServer\Js;

use EasyWeChat\Foundation\Core\AbstractAPI;
use EasyWeChat\Support\Str;
use EasyWeChat\Support\Url as UrlHelper;

/**
 * Class Js.
 */
class Js extends \EasyWeChat\OfficialAccount\Js\Js
{
    /**
     * Ticket cache prefix.
     */
    const TICKET_CACHE_PREFIX = 'overtrue.wechat.corp.jsapi_ticket.';

    /**
     * Api of ticket.
     */
    const API_TICKET = 'https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket';


    /**
     * Get jsticket.
     *
     * @param bool $forceRefresh
     *
     * @return string
     */
    public function ticket($forceRefresh = false)
    {
        $key = self::TICKET_CACHE_PREFIX.$this->getAccessToken()->getClientId();
        $ticket = $this->getCache()->fetch($key);

        if (!$forceRefresh && !empty($ticket)) {
            return $ticket;
        }

        $result = $this->parseJSON('get', [self::API_TICKET]);

        $this->getCache()->save($key, $result['ticket'], $result['expires_in'] - 500);

        return $result['ticket'];
    }


}
