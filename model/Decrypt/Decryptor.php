<?php

namespace Alohomora\model\Decrypt;

use Alohomora\model\Chunk\Chunk;

class Decryptor
{
    private $inputDirectory;
    private $fileName;
    private $privateKey;
    private $passphrase;

    /**
     * Decryptor constructor.
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
     * @return mixed
     */
    public function getDecryptedData()
    {
        return $this->decryptData();
    }

    /**
     * @return mixed
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
     * @return array
     * @throws \Error
     */
    private function getFileContents() : array
    {
        $directory = $this->inputDirectory . '/' . $this->fileName;

        if (!is_dir($directory)) throw new \Error("Given directory '$directory' doesn't exist.");

        $filenames = array_diff(scandir($directory, SCANDIR_SORT_DESCENDING), array('..', '.'));

        $files = [];

        foreach ($filenames as $file) {
            $files[] = file_get_contents($directory . '/' . $file);
        }

        return $files;
    }

    /**
     * @param string $file
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

    private function parsePrivateKey() {
        return openssl_get_privatekey($this->privateKey, $this->passphrase);
    }
}
