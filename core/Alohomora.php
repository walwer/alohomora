<?php
namespace Alohomora\core;

use Alohomora\model\Chunk\ChunkFactory;
use Alohomora\model\Decrypt\Decryptor;
use Alohomora\model\Encrypt\EncryptFactory;
use Alohomora\model\File\FileFactory;

class Alohomora
{
    private $entry;
    private $outputPath;
    private $fileName;

    /**
     * Alohomora constructor.
     */
    public function __construct()
    {
    }

    /**
     * Sets the entry to encrypt
     * @param $entry
     */
    public function setEntry($entry) : void
    {
        $this->entry = json_encode($entry);
    }

    /**
     * Sets the file name of the entry
     * This name will be md5 hashed to also cover
     * the primary name of the file
     * @param string $name
     */
    public function setFileName(string $name) : void
    {
        $this->fileName = $name;
    }

    /**
     * Encrypts the given string entry
     * @param string $publicKey
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
     * Sets the destination directory of encrypted files
     * @param string $output
     */
    public function setOutputDirectory(string $output) : void
    {
        $this->outputPath = $output;
    }

    /**
     * Decrypts the encrpted data in the specified directory with specified name (key)
     * with private key
     * and with optional use of passphrase of private key
     * @param string $directory
     * @param string $fileName
     * @param string $privateKey
     * @param string $keyPassphrase
     * @return mixed
     */
    public function decryptData(string $directory, string $fileName, string $privateKey, string $keyPassphrase = '')
    {
        $decryptor = new Decryptor($directory, $fileName, $privateKey, $keyPassphrase);

        return $decryptor->getDecryptedData();
    }
}
