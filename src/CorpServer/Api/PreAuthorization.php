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
 * PreAuthorization.php.
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

use EasyWeChat\Exceptions\InvalidArgumentException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class PreAuthorization extends AbstractCorpServer
{
    /**
     * Create pre auth code url.
     */
    const CREATE_PRE_AUTH_CODE = 'https://qyapi.weixin.qq.com/cgi-bin/service/get_pre_auth_code';

    /**
     * Pre auth link.
     */
    const PRE_AUTH_LINK = 'https://qy.weixin.qq.com/cgi-bin/loginpage?suite_id=%s&pre_auth_code=%s&redirect_uri=%s';
    
    /**
     * Get pre auth code.
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public function getCode()
    {
        $data = [
            'suite_id' => $this->getClientId(),
        ];

        $result = $this->parseJSON('json', [self::CREATE_PRE_AUTH_CODE, $data]);

        if (empty($result['pre_auth_code'])) {
            throw new InvalidArgumentException('Invalid response.');
        }

        return $result['pre_auth_code'];
    }

    /**
     * Redirect to WeChat PreAuthorization page.
     *
     * @param string $url
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirect($url)
    {
        return new RedirectResponse(
            sprintf(self::PRE_AUTH_LINK, $this->getClientId(), $this->getCode(), urlencode($url))
        );
    }
}
