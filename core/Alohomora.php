<?php
namespace Alohomora\core;

use Alohomora\model\Chunk\ChunkFactory;
use Alohomora\model\Decrypt\Decryptor;
use Alohomora\model\Encrypt\EncryptFactory;
use Alohomora\model\File\FileFactory;

class Alohomora
{
    /** @var string */
    private $entry;

    /** @var string */
    private $outputPath;

    /** @var string */
    private $fileName;

    /**
     * @param array $entry
     *
     * @return void
     */
    public function setEntry(array $entry) : void
    {
        $this->entry = json_encode($entry);
    }

    /**
     * @param string $name
     *
     * @return void
     */
    public function setFileName(string $name) : void
    {
        $this->fileName = $name;
    }

    /**
     * @param string $publicKey
     *
     * @return void
     */
    public function encryptEntry(string $publicKey) : void
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
     *
     * @return void
     */
    public function setOutputDirectory(string $output) : void
    {
        $this->outputPath = $output;
    }

    /**
     * @param string $directory
     * @param string $fileName
     * @param string $privateKey
     * @param string $keyPassphrase
     *
     * @return array
     */
    public function decryptData(string $directory, string $fileName, string $privateKey, string $keyPassphrase = '')
    {
        $decryptor = new Decryptor($directory, $fileName, $privateKey, $keyPassphrase);

        return $decryptor->getDecryptedData();
    }
}
