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
 * AccessToken.php.
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

use EasyWeChat\Exceptions\HttpException;
use EasyWeChat\Foundation\Core\AccessToken as BaseAccessToken;

class ProviderAccessToken extends BaseAccessToken
{

    /**
     * API.
     */
    const API_TOKEN_GET = 'https://qyapi.weixin.qq.com/cgi-bin/service/get_provider_token';

    /**
     * {@inheritdoc}.
     */
    protected $queryName = 'access_token';

    /**
     * {@inheritdoc}.
     */
    protected $tokenJsonKey = 'provider_access_token';

    /**
     * {@inheritdoc}.
     */
    protected $prefix = 'easywechat.corp_server.provider_access_token.';


    /**
     * {@inheritdoc}.
     */
    public function requestFields(): array
    {
        return [
            'corpid'     => $this->getClientId(),
            'provider_secret' => $this->getClientSecret(),
        ];
    }


    /**
     * Get the access token from WeChat server.
     *
     * @throws \EasyWeChat\Exceptions\HttpException
     *
     * @return array
     */
    public function getTokenFromServer()
    {
        $http = $this->getHttp();

        $result = $http->parseJSON($http->json(static::API_TOKEN_GET, $this->requestFields()));

        if (empty($result[$this->tokenJsonKey])) {
            throw new HttpException('Request AccessToken fail. response: '.json_encode($result,
                    JSON_UNESCAPED_UNICODE));
        }

        return $result;
    }

}
