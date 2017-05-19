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
 * @see      https://github.com/overtrue
 * @see      http://overtrue.me
 */

namespace EasyWeChat\CorpServer;

use EasyWeChat\Support\Traits\PrefixedContainer;

/**
 * Class CorpServer.
 *
 * @property \EasyWeChat\CorpServer\Api\BaseApi $api
 * @property \EasyWeChat\CorpServer\Server\Guard $server
 *
 */
class CorpServerQa
{
    use PrefixedContainer;

//    /**
//     * Quick access to the base-api.
//     *
//     * @param string $method
//     * @param array  $args
//     *
//     * @return mixed
//     */
//    public function __call($method, $args)
//    {
//        return call_user_func_array([$this->api, $method], $args);
//    }
}
