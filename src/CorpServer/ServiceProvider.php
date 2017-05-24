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

use EasyWeChat\CorpServer\Api\BaseApi;
use EasyWeChat\CorpServer\Api\LoginUserInfo;
use EasyWeChat\CorpServer\Api\PreAuthorization;
use EasyWeChat\CorpServer\EventHandlers;
use EasyWeChat\Foundation\Application;
use Overtrue\Socialite\SocialiteManager as Socialite;
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
        $suites = $pimple['config']['corp_server']['suites'];


        foreach ($suites as $key => $suite) {

            $pimple["corp_server_$key"] = function ($pimple) use ($key) {
                return new CorpServer($pimple, $key);
            };


            $pimple["corp_server_$key.api"] = function ($pimple) use ($key) {
                return new BaseApi(
                    $pimple["corp_server_$key.access_token"],
                    $pimple['request']
                );
            };

            $pimple["corp_server_$key.pre_auth"] = $pimple["corp_server_$key.pre_authorization"] = function ($pimple
            ) use ($key) {
                return new PreAuthorization(
                    $pimple["corp_server_$key.access_token"],
                    $pimple['request']
                );
            };

            $pimple["corp_server_$key.login_user"] = function (
                $pimple
            ) use ($key) {
                return new LoginUserInfo(
                    $pimple["corp_server_$key.provider_access_token"],
                    $pimple['request']
                );
            };


            $pimple["corp_server_$key.handlers.suite_ticket"] = function ($pimple) use ($key) {
                return new EventHandlers\SuiteTicket($pimple["corp_server_$key.suite_ticket"]);
            };
            $pimple["corp_server_$key.handlers.create_auth"] = function () {
                return new EventHandlers\CreateAuth();
            };
            $pimple["corp_server_$key.handlers.change_auth"] = function () {
                return new EventHandlers\ChangeAuth();
            };
            $pimple["corp_server_$key.handlers.cancel_auth"] = function () {
                return new EventHandlers\CancleAuth();
            };

            $pimple["corp_server_$key.app"] = function ($pimple) {
                return new Application($pimple['config']->toArray());
            };

            // OAuth for CorpServer.
            $pimple["corp_server_$key.oauth"] = function ($pimple) use ($key) {
                $callback = $this->prepareCallbackUrl($pimple);
                $scopes = $pimple['config']->get('corp_server.oauth.scopes', []);
                $socialite = (new Socialite([
                    'wechat_corp_server' => [
                        'client_id'     => $pimple["corp_server_$key.authorizer_access_token"]->getCorpId(),
                        'client_secret' => $pimple["corp_server_$key.authorizer_access_token"],
                        'redirect'      => $callback,
                    ],
                ]))->driver('wechat_corp_server');

                if (!empty($scopes)) {
                    $socialite->scopes($scopes);
                }

                return $socialite;
            };

        }
    }

    /**
     * Prepare the OAuth callback url for wechat.
     *
     * @param Container $pimple
     *
     * @return string
     */
    private function prepareCallbackUrl($pimple)
    {
        $callback = $pimple['config']->get('oauth.callback');
        if (0 === stripos($callback, 'http')) {
            return $callback;
        }
        $baseUrl = $pimple['request']->getSchemeAndHttpHost();

        return $baseUrl.'/'.ltrim($callback, '/');
    }
}
