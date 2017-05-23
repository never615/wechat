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

    private $suiteId;

    /**
     * Constructor.
     *
     * @param string $appId
     * @param string $token
     * @param string $AESKey
     * @param        $suiteId
     */
    public function __construct($appId, $token, $AESKey, $suiteId)
    {
        parent::__construct($appId, $token, $AESKey);

        $this->appId = $appId;
        $this->token = $token;
        $this->AESKey = $AESKey;
        $this->blockSize = 32;
        $this->suiteId = $suiteId;
    }

    /**
     * @return string
     */
    public function getAppId()
    {
        return $this->appId;
    }

    /**
     * @param string $appId
     */
    public function setAppId($appId)
    {
        $this->appId = $appId;
    }
    

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

    /**
     * Decrypt message.
     *
     * @param string $encrypted
     * @param string $appId
     *
     * @return string
     *
     * @throws EncryptionException
     */
    protected function decrypt($encrypted, $appId)
    {
        try {
            $key = $this->getAESKey();
            $ciphertext = base64_decode($encrypted, true);
            $iv = substr($key, 0, 16);

            $decrypted = openssl_decrypt($ciphertext, 'aes-256-cbc', $key, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING, $iv);
        } catch (BaseException $e) {
            throw new EncryptionException($e->getMessage(), EncryptionException::ERROR_DECRYPT_AES);
        }

        try {
            $result = $this->decode($decrypted);
            if (strlen($result) < 16) {
                return '';
            }

            $content = substr($result, 16, strlen($result));
            $listLen = unpack('N', substr($content, 0, 4));
            $xmlLen = $listLen[1];
            $xml = substr($content, 4, $xmlLen);
            $fromAppId = trim(substr($content, $xmlLen + 4));
        } catch (BaseException $e) {
            throw new EncryptionException($e->getMessage(), EncryptionException::ERROR_INVALID_XML);
        }

        //appid(corpid:应用套件的)验证的时候使用;suiteid接收消息回调(ticket等时使用)
        //appid可以重新设置,用来接受具体企业号的用户消息
        if ($fromAppId !== $appId && $fromAppId !== $this->suiteId) {
            throw new EncryptionException('Invalid appId.', EncryptionException::ERROR_INVALID_APPID);
        }

        return $xml;
    }

}
