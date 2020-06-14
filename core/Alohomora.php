<?php

namespace Alohomora\core;

use Alohomora\model\Chunk\ChunkFactory;
use Alohomora\model\Encrypt\EncryptFactory;
use Alohomora\model\File\FileFactory;

class Alohomora
{
    private $entry;
    private $outputPath;
    private $fileName;

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

    public function setFileName(string $name)
    {
        $this->fileName = $name;
    }

    /**
     * @label('This is the main function of Alohomora that encrypt the given entry')
     * @param string $publicKey
     */
    public function encryptEntry(string $publicKey)
    {
        $chunkFactory = new ChunkFactory();
        $chunks = $chunkFactory->splitStringToChunks($this->entry);

        $encryptionFactory = new EncryptFactory($chunks);
        $encryptionFactory->setPublicKey($publicKey);
        $encryptedChunks = $encryptionFactory->getEncryptedChunks();

        $fileFactory = new FileFactory($this->outputPath, $this->fileName);
        $fileFactory->createFilesFromChunks($encryptedChunks);
    }

    /**
     * @param string $output
     */
    public function setOutputDirectory(string $output)
    {
        $this->outputPath = $output;
    }
}