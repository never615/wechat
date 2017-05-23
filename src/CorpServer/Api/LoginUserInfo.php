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
 * LoginProvider.php.
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


use EasyWeChat\CorpServer\Core\ProviderAccessToken;
use EasyWeChat\Foundation\Core\AbstractAPI;
use Symfony\Component\HttpFoundation\Request;

class LoginUserInfo extends AbstractAPI
{
    /**
     * Request.
     *
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * Get the login user information
     */
    const GET_PROVIDER_TOKEN = 'https://qyapi.weixin.qq.com/cgi-bin/service/get_login_info';

    /**
     * AbstractCorpServer constructor.
     *
     * @param \EasyWeChat\CorpServer\Core\ProviderAccessToken $accessToken
     * @param \Symfony\Component\HttpFoundation\Request       $request
     */
    public function __construct(ProviderAccessToken $accessToken, Request $request)
    {
        parent::__construct($accessToken);

        $this->request = $request;
    }

    /**
     * Get the login user information
     *
     * @param $authCode
     * @return \EasyWeChat\Support\Collection
     */
    public function getUserInfo($authCode=null)
    {
        $params = [
            "auth_code" => $authCode ?: $this->request->get('auth_code'),
        ];

        return $this->parseJSON('json', [self::GET_PROVIDER_TOKEN, $params]);
    }

    /**
     * Get CropServer SuiteId.
     *
     * @return string
     */
    public function getClientId()
    {
        return $this->getAccessToken()->getClientId();
    }

    /**
     * Get CropSErver SuiteSecret
     *
     * @return string
     */
    public function getClientSecret()
    {
        return $this->getAccessToken()->getClientSecret();
    }

}
