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
 * QYServerServiceProvider.php.
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

namespace EasyWeChat\CorpServer;

use EasyWeChat\CorpServer\EventHandlers;
use Pimple\Container;
use Pimple\ServiceProviderInterface;


class ServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $pimple['corp_server'] = function ($pimple) {
            return new CorpServer($pimple);
        };

        // Authorization events handlers.
        $pimple['corp_server.handlers.suite_ticket'] = function ($pimple) {
            return new EventHandlers\SuiteTicket($pimple['corp_server.suite_ticket']);
        };
        $pimple['corp_server.handlers.create_auth'] = function () {
            return new EventHandlers\CreateAuth();
        };
        $pimple['corp_server.handlers.change_auth'] = function () {
            return new EventHandlers\ChangeAuth();
        };
        $pimple['corp_server.handlers.cancel_auth'] = function () {
            return new EventHandlers\CancleAuth();
        };

    }
}
