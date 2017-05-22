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
 * BaseApi.php.
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

namespace EasyWeChat\CorpServer\Api;

class BaseApi extends AbstractCorpServer
{
    /**
     * Get info (auth_corp_info,auth_info,auth_user_info ) api.
     */
    const GET_INFO = 'https://qyapi.weixin.qq.com/cgi-bin/service/get_permanent_code';

    /**
     * Get authorizer token api.
     */
    const GET_AUTHORIZER_TOKEN = 'https://qyapi.weixin.qq.com/cgi-bin/service/get_corp_token';

    /**
     * Get authorizer info api.
     */
    const GET_AUTH_INFO = 'https://qyapi.weixin.qq.com/cgi-bin/service/get_auth_info';

    /**
     * Get authorization info.
     *
     * @param $authCode
     *
     * @return \EasyWeChat\Support\Collection
     */
    public function getAuthorizationInfo($authCode = null)
    {
        $params = [
            'suite_id'  => $this->getClientId(),
            "auth_code" => $authCode,
        ];

        return $this->parseJSON('json', [self::GET_AUTH_INFO, $params]);
    }

    /**
     * Get authorization info.
     *
     * @param $corpId
     * @param $permanentCode
     * @return \EasyWeChat\Support\Collection
     */
    public function getAuthorizerInfo($corpId, $permanentCode)
    {
        $params = [
            "suite_id"       => $this->getClientId(),
            "auth_corpid"    => $corpId,
            "permanent_code" => $permanentCode,
        ];

        return $this->parseJSON('json', [self::GET_AUTH_INFO, $params]);
    }

    /**
     * Get authorizer token.
     *
     * It doesn't cache the authorizer-access-token.
     * So developers should NEVER call this method.
     * It'll called by: AuthorizerAccessToken::renewAccessToken()
     *
     * @param $authCorpId
     * @param $permanentCode
     * @return \EasyWeChat\Support\Collection
     *
     */
    public function getAuthorizerToken($authCorpId, $permanentCode)
    {
        $params = [
            "suite_id"       => $this->getClientId(),
            "auth_corpid"    => $authCorpId,
            "permanent_code" => $permanentCode,
        ];

        return $this->parseJSON('json', [self::GET_AUTHORIZER_TOKEN, $params]);
    }
}
