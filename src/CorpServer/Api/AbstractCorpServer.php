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
 * AbstractCorpServer.php.
 *
 * Part of Overtrue\WeChat.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author    never615 <never615@gmail.com>
 * @copyright 2017
 *
 * @see      https://github.com/overtrue
 * @see      http://overtrue.me
 */

namespace EasyWeChat\CorpServer\Api;

use EasyWeChat\Foundation\Core\AbstractAPI;
use EasyWeChat\OpenPlatform\Core\AccessToken;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractCorpServer extends AbstractAPI
{
    /**
     * Request.
     *
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * AbstractOpenPlatform constructor.
     *
     * @param \EasyWeChat\CorpServer\AccessToken      $accessToken
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function __construct(AccessToken $accessToken, Request $request)
    {
        parent::__construct($accessToken);

        $this->request = $request;
    }

    /**
     * Get OpenPlatform AppId.
     *
     * @return string
     */
    public function getClientId()
    {
        return $this->getAccessToken()->getClientId();
    }
}
