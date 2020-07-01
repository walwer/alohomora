<?php

namespace Alohomora\model\Encrypt;

use Alohomora\model\Chunk\Chunk;

class EncryptFactory
{
    private $publicKey;
    private $chunks;

    public function __construct(array $chunks)
    {
        $this->chunks = $chunks;
    }

    /**
     * @param string $key
     */
    public function setPublicKey(string $key)
    {
        $this->publicKey = $key;
    }

    /**
     * @return array
     */
    public function getEncryptedChunks()
    {
        $encrypted = [];

        foreach ($this->chunks as $key => $chunk) {
            $encrypted[] = $this->_encryptData($chunk);
        }

        return $encrypted;
    }

    /**
     * @param Chunk $chunk
     * @return array
     */
    private function _encryptData(Chunk $chunk)
    {
        $publicKey = openssl_get_publickey($this->publicKey);
        $encrypted = $e = NULL;
        $stringifiedChunk = $chunk->getJSONStringifiedChunk();
        openssl_seal($stringifiedChunk, $encrypted, $e, array($publicKey));
        return ['c' => base64_encode($encrypted), 'e' => base64_encode($e[0])];
    }
}