<?php

namespace Alohomora\model\Encrypt;

class EncryptFactory
{
    private $publicKey;
    private $chunks;

    public function __construct(array $chunks)
    {
        $this->chunks = $chunks;
    }

    public function setPublicKey(string $key)
    {
        $this->publicKey = $key;
    }

    public function getEncryptedChunks()
    {
        $encrypted = [];

        foreach ($this->chunks as $key => $chunk) {
            $encrypted[] = $this->_encryptData($chunk);
        }

        return $encrypted;
    }

    private function _encryptData(string $chunk)
    {
        $publicKey = openssl_get_publickey($this->publicKey);
        $encrypted = $e = NULL;
        openssl_seal($chunk, $encrypted, $e, array($publicKey));
        return ['c' => base64_encode($encrypted), 'e' => base64_encode($e[0])];
    }
}