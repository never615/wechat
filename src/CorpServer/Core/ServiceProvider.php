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
        $suites = $pimple['config']['corp_server']['suites'];

        foreach ($suites as $key => $suite) {
            $pimple["corp_server_$key.suite_ticket"] = function ($pimple) use ($key, $suite) {
                return new Ticket(
                    $suite['suite_id'],
                    $pimple['cache']
                );
            };

            $pimple["corp_server_$key.access_token"] = function ($pimple) use ($key, $suite) {
                $accessToken = new AccessToken(
                    $suite['suite_id'],
                    $suite['secret']
                );

                $accessToken->setCache($pimple['cache'])
                    ->setSuiteTicket($pimple["corp_server_$key.suite_ticket"]);

                return $accessToken;
            };

            $pimple["corp_server_$key.authorizer_access_token"] = function ($pimple) use ($key, $suite) {
                $accessToken = new AuthorizerAccessToken(
                    $suite["suite_id"]
                );
                $accessToken->setApi($pimple["corp_server_$key.api"])
                    ->setCache($pimple['cache']);

                return $accessToken;
            };
        }
    }
}
