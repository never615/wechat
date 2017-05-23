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
 * AuthorizerAccessToken.php.
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

namespace EasyWeChat\CorpServer\Core;

use EasyWeChat\CorpServer\Api\BaseApi;
use EasyWeChat\Exceptions\Exception;
use EasyWeChat\Foundation\Core\AccessToken as BaseAccessToken;

/**
 * Class AuthorizerAccessToken.
 *
 * AuthorizerAccessToken is responsible for the access token of the authorizer,
 * the complexity is that this access token also requires the refresh token
 * of the authorizer which is acquired by the open platform authorizer process.
 *
 * This completely overrides the original AccessToken.
 */
class AuthorizerAccessToken extends BaseAccessToken
{

    /**
     * {@inheritdoc}.
     */
    protected $prefix = 'easywechat.corp_server.authorizer_access_token.';

    /**
     * {@inheritdoc}.
     */
    protected $tokenJsonKey = 'suite_access_token';

    /**
     * Api instance.
     *
     * @var \EasyWeChat\CorpServer\Api\BaseApi
     */
    protected $api;

    /**
     * @var \EasyWeChat\CorpServer\EventHandlers\CreateAuth
     */
    protected $createAuth;

    /**
     * Authorizer Corp id.
     *
     * @var string
     */
    protected $corpId;

    /**
     * Authorizer permanent code.
     *
     * @var string
     */
    protected $permanentCode;

    /**
     * Set the api instance.
     *
     * @param \EasyWeChat\OpenPlatform\Api\BaseApi $api
     *
     * @return $this
     */
    public function setApi(BaseApi $api)
    {
        $this->api = $api;

        return $this;
    }

    /**
     * Set the authorizer app id.
     *
     * @param string $corpId
     *
     * @return $this
     */
    public function setCorpId(string $corpId)
    {
        $this->corpId = $corpId;

        return $this;
    }

    /**
     * Set the authorizer refresh token.
     *
     * @param string $permanentCode
     *
     * @return $this
     */
    public function setPermanentCode(string $permanentCode)
    {
        $this->permanentCode = $permanentCode;

        return $this;
    }

    /**
     * {@inheritdoc}.
     */
    public function getTokenFromServer()
    {
        return $this->api->getAuthorizerToken(
            $this->corpId, $this->permanentCode
        );
    }

    /**
     * Return the authorizer appId.
     *
     * @throws \EasyWeChat\Exceptions\Exception
     *
     * @return string
     */
    public function getCorpId(): string
    {
        if (!$this->corpId) {
            throw new Exception('Authorizer Corp Id is not present, you may not make the authorizer yet.');
        }

        return $this->corpId;
    }

    /**
     * {@inheritdoc}.
     */
    public function getCacheKey(): string
    {
        if (is_null($this->cacheKey)) {
            return $this->prefix.$this->getClientId().$this->corpId;
        }

        return $this->cacheKey;
    }
}
