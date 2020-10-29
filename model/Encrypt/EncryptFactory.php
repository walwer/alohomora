<?php
namespace Alohomora\model\Encrypt;

use Alohomora\model\Chunk\Chunk;

class EncryptFactory
{
    /** @var string */
    private $publicKey;

    /** @var Chunk[] */
    private $chunks;

    /**
     * EncryptFactory constructor.
     * @param array $chunks
     *
     * @return void
     */
    public function __construct(array $chunks)
    {
        /** @var Chunk[] $chunks */
        $this->chunks = $chunks;
    }

    /**
     * @param string $key
     *
     * @return void
     */
    public function setPublicKey(string $key)
    {
        $this->publicKey = $key;
    }

    /**
     * Returns array of encrypted chunks
     * @return array
     */
    public function getEncryptedChunks()
    {
        $encrypted = [];

        foreach ($this->chunks as $key => $chunk) {
            $encrypted[] = $this->encryptData($chunk);
        }

        return $encrypted;
    }

    /**
     * Encrypts single Chunk with public key
     * @param Chunk $chunk
     *
     * @return array
     */
    private function encryptData(Chunk $chunk)
    {
        $publicKey = openssl_get_publickey($this->publicKey);
        $encrypted = $e = NULL;

        $stringifiedChunk = $chunk->getJSONStringifiedChunk();
        openssl_seal($stringifiedChunk, $encrypted, $e, array($publicKey));

        return ['c' => base64_encode($encrypted), 'e' => base64_encode($e[0])];
    }
}
