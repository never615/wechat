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
 * Ticket.php.
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

namespace EasyWeChat\CorpServer\Core;

use Doctrine\Common\Cache\Cache;
use EasyWeChat\Exceptions\RuntimeException;

class Ticket
{
    /**
     * Cache manager.
     *
     * @var \Doctrine\Common\Cache\Cache
     */
    protected $cache;

    /**
     * App Id.
     *
     * @var string
     */
    protected $appId;

    /**
     * Cache Key.
     *
     * @var string
     */
    private $cacheKey;

    /**
     * Cache key prefix.
     *
     * @var string
     */
    protected $prefix = 'easywechat.corp_server.suite_ticket.';

    /**
     * Ticket constructor.
     *
     * @param string                       $appId
     * @param \Doctrine\Common\Cache\Cache $cache
     */
    public function __construct($appId, Cache $cache)
    {
        $this->appId = $appId;
        $this->cache = $cache;
    }

    /**
     * Set suite ticket to the cache.
     *
     * @param string $ticket
     *
     * @return bool
     */
    public function setTicket($ticket)
    {
        return $this->cache->save($this->getCacheKey(), $ticket);
    }

    /**
     * Get suite ticket.
     *
     * @return string
     * @throws RuntimeException
     */
    public function getTicket()
    {
        if ($cached = $this->cache->fetch($this->getCacheKey())) {
            return $cached;
        }

        throw new RuntimeException('suite ticket does not exists.');
    }

    /**
     * Set suite ticket cache key.
     *
     * @param string $cacheKey
     *
     * @return $this
     */
    public function setCacheKey($cacheKey)
    {
        $this->cacheKey = $cacheKey;

        return $this;
    }

    /**
     * Get suite ticket cache key.
     *
     * @return string $this->cacheKey
     */
    public function getCacheKey()
    {
        if (is_null($this->cacheKey)) {
            return $this->prefix.$this->appId;
        }

        return $this->cacheKey;
    }
}
