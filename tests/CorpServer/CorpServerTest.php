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
use EasyWeChat\Foundation\Application;
use EasyWeChat\OpenPlatform\Core\AccessToken;
use EasyWeChat\OpenPlatform\Core\VerifyTicket;
use EasyWeChat\Tests\TestCase;
use Mockery as m;

class CorpServerTest extends TestCase
{
    public function testCorpServer()
    {
        $app=$this->make();

        $corpServer = $app->corp_server_qa;

//        $this->assertInstanceOf('EasyWeChat\OpenPlatform\Api\BaseApi', $corpServer->api);
//        $this->assertInstanceOf('EasyWeChat\OpenPlatform\Api\PreAuthorization', $corpServer->pre_auth);
//        $this->assertInstanceOf('EasyWeChat\OpenPlatform\Api\PreAuthorization', $corpServer->pre_authorization);
        $this->assertInstanceOf('EasyWeChat\CorpServer\Server\Guard', $corpServer->server);
    }

//    public function testMakeAuthorizer()
//    {
//        $ticket = new Ticket('open-platform-appid@999', new ArrayCache());
//        $ticket->setTicket('ticket');
//
//        $cache = m::mock('Doctrine\Common\Cache\Cache', function ($mock) {
//            $mock->shouldReceive('fetch')->andReturn('thisIsACachedToken');
//            $mock->shouldReceive('save')->andReturnUsing(function ($key, $token, $expire) {
//                return $token;
//            });
//        });
//        $accessToken = new AccessToken(
//            'corp-server-corpid@999',
//            'corp-server-secret'
//        );
//        $accessToken->setCache($cache);
//        $accessToken->setVerifyTicket($ticket);
//
//        $app = $this->make();
//        $app['open_platform.access_token'] = $accessToken;
//        $newApp = $app->open_platform->createAuthorizerApplication('authorizer-appid@999', 'authorizer-refresh-token');
//
//        $this->assertInstanceOf('EasyWeChat\OpenPlatform\Core\AuthorizerAccessToken', $newApp->access_token);
//        $this->assertEquals('authorizer-appid@999', $newApp->access_token->getAppId());
//    }

    /**
     * Makes application.
     *
     * @return Application
     */
    private function make()
    {
        $config = [
            'corp_server' => [
                'suites'         => [
                    "qa" => [
                        "suite_id" => "tj341a2f8bbd49907a",
                        "secret"   => "bYxM96UgDtapI8o3k9JHsOJvbQC1FAT2zs57iZqZ8jXoT6UXS5PU_hYRzJZpGsY5",
                        'token'    => "XS7kNttzYUpQ663EYk5AEc5j84oRs9u",
                        'aes_key'  => "Lw4qySzZrBqz8iYf5n1MqGQMUElQjxAUTvCgAh6AnO5",
                    ],
                ],
                "corp_id"        => "wx6225037d8b9390be",
                "providersecret" => "87wH5fiFdQcw5nS6nZKyKXTULc-3ka5soUYg85RS3RIaNpTEp-aZVs7BowsiWbaE",
            ],
        ];

        return new Application($config);
    }
}
