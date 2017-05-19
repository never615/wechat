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
        $corpServer = $this->make()->corp_server;

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
                'corp_id' => 'your-app-id',
                'secret' => 'your-app-secret',
                'token' => 'your-token',
                'aes_key' => 'your-ase-key',
            ],
        ];

        return new Application($config);
    }
}
