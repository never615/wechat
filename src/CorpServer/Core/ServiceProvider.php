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
 * ServiceProvider.php.
 *
 * Part of Overtrue\WeChat.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author    never615 <never615@gmail.com>
 * @copyright 2017
 *
 * @see       https://github.com/overtrue/wechat
 * @see       http://overtrue.me
 */

namespace EasyWeChat\CorpServer\Core;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}.
     */
    public function register(Container $pimple)
    {

        $pimple['corp_server.suite_ticket'] = function ($pimple) {
            return new Ticket(
                $pimple['config']['corp_server']['corp_id'],
                $pimple['cache']
            );
        };

//        $container['corp_server.access_token'] = function ($container) {
//            $accessToken = new AccessToken(
//                $container['config']['corp_server']['corp_id'],
//                $container['config']['corp_server']['secret']
//            );
//            $accessToken->setCache($container['cache'])
//                ->setVerifyTicket($container['corp_server.suite_ticket']);
//
//            return $accessToken;
//        };
        
        
    }
}
