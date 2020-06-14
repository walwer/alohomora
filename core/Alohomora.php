<?php

namespace Alohomora\core;

use Alohomora\model\Chunk\ChunkFactory;
use Alohomora\model\Encrypt\EncryptFactory;
use Alohomora\model\File\FileFactory;

class Alohomora
{
    private $entry;
    private $publicKey;
    private $privateKey;
    private $outputPath;

    public function __construct()
    {
    }

    /**
     * @param $entry
     */
    public function setEntry($entry)
    {
        $this->entry = json_encode($entry);
    }

    /**
     * @param string $key
     */
    public function setPublicKey(string $key)
    {
        $this->publicKey = $key;
    }

    /**
     * @param string $key
     */
    public function setPrivateKey(string $key)
    {
        $this->privateKey = $key;
    }

    /**
     * @label('This is the main function of Alohomora that encrypt the given entry')
     */
    public function encryptEntry()
    {
        $chunkFactory = new ChunkFactory();
        $chunks = $chunkFactory->splitStringToChunks($this->entry);

        /* TODO: Here chunks should be encrypted */
        $encryptionFactory = new EncryptFactory($chunks);
        $encryptionFactory->setPublicKey($this->publicKey);
        $encryptedChunks = $encryptionFactory->getEncryptedChunks();

        $fileFactory = new FileFactory();
        $fileFactory->createFilesFromChunks($encryptedChunks);
    }

    /**
     * @param string $output
     */
    public function setOutputPath(string $output)
    {
        $this->outputPath = $output;
    }
}