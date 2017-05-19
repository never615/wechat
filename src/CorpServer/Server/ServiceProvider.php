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

namespace EasyWeChat\CorpServer\Server;

use EasyWeChat\CorpServer\Encryption\Encryptor;
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
            $pimple["corp_server_$key.encryptor"] = function ($pimple) use ($suite) {
                return new Encryptor(
                    $suite['suite_id'],
                    $suite['token'],
                    $suite['aes_key']
                );
            };

            $pimple["corp_server_$key.server"] = function ($pimple) use ($key, $suite) {
                $server = new Guard($suite['token']);
                $server->debug($pimple['config']['debug']);
                $server->setEncryptor($pimple["corp_server_$key.encryptor"]);
                $server->setHandlers([
                    Guard::EVENT_CREATE_AUTH  => $pimple["corp_server_$key.handlers.create_auth"],
                    Guard::EVENT_CANCEL_AUTH  => $pimple["corp_server_$key.handlers.cancel_auth"],
                    Guard::EVENT_CHANGE_AUTH  => $pimple["corp_server_$key.handlers.change_auth"],
                    Guard::EVENT_SUITE_TICKET => $pimple["corp_server_$key.handlers.suite_ticket"],
                ]);

                return $server;
            };
        }
    }
}
