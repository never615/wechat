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
 * Encryptor.php.
 *
 * @author    never615 <never615@gmail.com>
 * @copyright 2017
 *
 * @see       https://github.com/overtrue
 * @see       http://overtrue.me
 */

namespace EasyWeChat\CorpServer\Encryption;

use EasyWeChat\OfficialAccount\Encryption\EncryptionException;
use EasyWeChat\OfficialAccount\Encryption\Encryptor as BaseEncryptor;
use EasyWeChat\Support\XML;
use Exception as BaseException;

class Encryptor extends BaseEncryptor
{

    /**
     * Decrypt message.
     *
     * @param string  $msgSignature
     * @param string  $nonce
     * @param string  $timestamp
     * @param string  $content
     *
     * @param boolean $verify
     * @return array
     * @throws EncryptionException
     */
    public function decryptMsg($msgSignature, $nonce, $timestamp, $content, $verify = false)
    {
        if ($verify) {
            $encrypted = $content;
        } else {
            try {
                $array = XML::parse($content);
            } catch (BaseException $e) {
                throw new EncryptionException('Invalid xml.', EncryptionException::ERROR_PARSE_XML);
            }
            $encrypted = $array['Encrypt'];
        }

        $signature = $this->getSHA1($this->token, $timestamp, $nonce, $encrypted);
        if ($signature !== $msgSignature) {
            throw new EncryptionException('Invalid Signature.', EncryptionException::ERROR_INVALID_SIGNATURE);
        }

        if ($verify) {
            return $this->decrypt($encrypted, $this->appId);
        } else {
            return XML::parse($this->decrypt($encrypted, $this->appId));
        }
    }
    
}
