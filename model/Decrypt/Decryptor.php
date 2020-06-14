<?php

namespace Alohomora\model\Decrypt;

class Decryptor
{
    private $inputDirectory;
    private $fileName;
    private $privateKey;

    public function __construct(string $directory, string $fileName, string $privateKey)
    {
        $this->inputDirectory = $directory;
        $this->fileName = md5($fileName);
        $this->privateKey = $privateKey;
    }

    public function getDecryptedData()
    {
        return $this->_decryptData();
    }

    /**
     * @return array
     * @throws \Error
     */
    private function _getFileContents()
    {
        $directory = $this->inputDirectory . '/' . $this->fileName;
        if (!is_dir($directory)) throw new \Error("Given directory '$directory' doesn't exist.");
        $filenames = array_diff(scandir($directory, SCANDIR_SORT_DESCENDING), array('..', '.'));

        $files = [];

        foreach ($filenames as $file)
        {
            $files[] = file_get_contents($directory.'/'.$file);
        }

        return $files;
    }

    private function _decryptSingleFile(string $file)
    {
        $file = base64_decode($file);
        $content = NULL;
        openssl_open($file, $plaintext, NULL, $this->privateKey);
        return $content;
    }

    private function _decryptData()
    {
        $files = $this->_getFileContents();

        if(empty($files)) throw new \Error('No files to decode');

        foreach ($files as $file) {
            var_dump($this->_decryptSingleFile($file));
        }
    }
}