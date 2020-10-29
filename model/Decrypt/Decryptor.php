<?php
namespace Alohomora\model\Decrypt;

use Alohomora\model\Chunk\Chunk;

class Decryptor
{
    /** @var string  */
    private $inputDirectory;

    /** @var string  */
    private $fileName;

    /** @var string  */
    private $privateKey;

    /** @var string  */
    private $passphrase;

    /**
     * @param string $directory
     * @param string $fileName
     * @param string $privateKey
     * @param string $passphrase
     */
    public function __construct(string $directory, string $fileName, string $privateKey, string $passphrase)
    {
        $this->inputDirectory = $directory;
        $this->fileName = md5($fileName);
        $this->privateKey = $privateKey;
        $this->passphrase = $passphrase;
    }

    /**
     * @return array
     */
    public function getDecryptedData()
    {
        return $this->decryptData();
    }

    /**
     * Makes decruption loop of given array of encrypted files contents
     * @return array
     */
    private function decryptData()
    {
        $files = $this->getFileContents();

        if (empty($files)) throw new \Error('No files to decode');

        $contents = [];

        foreach ($files as $file) {
            $contents[] = $this->decryptSingleFile($file);
        }

        usort($contents, function(Chunk $a, Chunk $b) {
            return $a->getStart() <=> $b->getStart();
        });

        $result = array_reduce($contents, function(string $acc, Chunk $item) {
            return $acc.$item->getContent();
        }, "");

        return json_decode($result);
    }

    /**
     * Gets all files contents in from specified (md5) directory
     * and places them as base64 into array
     * @return array
     *
     * @throws \Error
     */
    private function getFileContents() : array
    {
        $directory = $this->inputDirectory . '/' . $this->fileName;

        if (!is_dir($directory)) {
            throw new \Error("Given directory '$directory' doesn't exist.");
        }

        $filenames = array_diff(scandir($directory, SCANDIR_SORT_DESCENDING), array('..', '.'));
        $files = [];

        foreach ($filenames as $file) {
            $files[] = file_get_contents($directory . '/' . $file);
        }

        return $files;
    }

    /**
     * Decrypts a single file given
     * @param string $file
     *
     * @return Chunk
     */
    private function decryptSingleFile(string $file) : Chunk
    {

        $file = explode('^', $file);
        $content = NULL;

        $privateKey = $this->parsePrivateKey();

        openssl_open(base64_decode($file[0]), $content, base64_decode($file[1]), $privateKey);

        $content = json_decode($content, true);

        if(isset($content['s']) && isset($content['e']) && isset($content['c'])) {
            return new Chunk($content['s'], $content['e'], $content['c']);
        } else {
            throw new \Error('Invalid or damaged data given');
        }
    }

    /**
     * Parses resource from file with private key
     * @return false|resource
     */
    private function parsePrivateKey() {
        return openssl_get_privatekey($this->privateKey, $this->passphrase);
    }
}
