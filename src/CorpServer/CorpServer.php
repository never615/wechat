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
 * CorpServer.php.
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

use EasyWeChat\Support\Str;
use EasyWeChat\Support\Traits\PrefixedContainer;
use Pimple\Container;

/**
 * Class CorpServer.
 *
 * @property \EasyWeChat\CorpServer\Api\BaseApi          $api
 * @property \EasyWeChat\CorpServer\Api\PreAuthorization $pre_auth
 * @property \EasyWeChat\CorpServer\Server\Guard         $server
 * @property \EasyWeChat\CorpServer\Core\AccessToken     $access_token
 * @property \EasyWeChat\CorpServer\Encryption\Encryptor    $encryptor
 *
 * @method \EasyWeChat\Support\Collection getAuthorizationInfo($authCode = null)
 * @method \EasyWeChat\Support\Collection getAuthorizerInfo($corpId, $permanentCode)
 *
 */
class CorpServer
{
    use PrefixedContainer;

    private $suiteKey = "";

    /**
     * ContainerAccess constructor.
     *
     * @param Container|\Pimple\Container $container
     * @param                             $suiteKey
     */
    public function __construct(Container $container, $suiteKey)
    {
        $this->container = $container;
        $this->suiteKey = $suiteKey;
    }


    /**
     * Create an instance of the EasyWeChat for the given authorizer.
     *
     * @param string $corpId        Authorizer AppId
     * @param string $permanentCode Authorizer refresh-token
     *
     * @return \EasyWeChat\Foundation\Application
     */
    public function createAuthorizerApplication(string $corpId, string $permanentCode)
    {
        $this->fetch('authorizer_access_token', function ($accessToken) use ($corpId, $permanentCode) {
            $accessToken->setCorpId($corpId);
            $accessToken->setPermanentCode($permanentCode);
        });

        return $this->fetch('app', function ($app) {
            $app['access_token'] = $this->fetch('authorizer_access_token');
//            $app['oauth'] = $this->fetch('oauth');
            $app['server'] = $this->fetch('server');
        });
    }

    /**
     * Quick access to the base-api.
     *
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        return call_user_func_array([$this->api, $method], $args);
    }

    /**
     * Gets a parameter or an object from pimple container.
     *
     * Get the `class basename` of the current class.
     * Convert `class basename` to snake-case and concatenation with dot notation.
     *
     * E.g. Class 'EasyWechat', $key foo -> 'easy_wechat_[suiteKey].foo'
     *
     * @param string $key The unique identifier for the parameter or object
     *
     * @return mixed The value of the parameter or an object
     *
     * @throws \InvalidArgumentException If the identifier is not defined
     */
    public function __get($key)
    {
        $className = basename(str_replace('\\', '/', static::class));

        $name = Str::snake($className).'_'.$this->suiteKey.'.'.$key;

        return $this->container->offsetGet($name);
    }

}
