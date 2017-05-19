<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyWeChat\Tests\CorpServer;

use Doctrine\Common\Cache\ArrayCache;
use EasyWeChat\CorpServer\Core\Ticket;
use EasyWeChat\Tests\TestCase;

class TicketTest extends TestCase
{
    /**
     * Get VerifyTicket instance.
     */
    public function getTicket($appId)
    {
        return new Ticket($appId, new ArrayCache());
    }

    /**
     * Tests that the verify ticket is properly cached.
     */
    public function testTicket()
    {
        $verifyTicket = $this->getTicket('foobar');

        $this->assertTrue($verifyTicket->setTicket('ticket@foobar'));
        $this->assertEquals('ticket@foobar', $verifyTicket->getTicket());
    }

    /**
     * Test cache key.
     */
    public function testCacheKey()
    {
        $verifyTicket = $this->getTicket('app-id');

        $this->assertEquals('easywechat.corp_server.suite_ticket.app-id', $verifyTicket->getCacheKey());

        $verifyTicket->setCacheKey('cache-key.app-id');

        $this->assertEquals('cache-key.app-id', $verifyTicket->getCacheKey());
    }
}
