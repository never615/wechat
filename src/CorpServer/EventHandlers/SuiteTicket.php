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
 * ComponentVerifyTicket.php.
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

namespace EasyWeChat\CorpServer\EventHandlers;




use EasyWeChat\CorpServer\Core\Ticket;

class SuiteTicket extends EventHandler
{
    /**
     * SuiteTicket.
     *
     */
    protected $ticket;

    /**
     * Constructor.
     *
     * @param Ticket $ticket
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * {@inheritdoc}.
     */
    public function handle($message)
    {
        $this->ticket->setTicket($message->get('SuiteTicket'));
    }
}
