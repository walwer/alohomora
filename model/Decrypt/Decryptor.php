<?php

namespace Alohomora\model\Decrypt;

class Decryptor
{
    private $inputDirectory;
    private $fileName;
    private $privateKey;
    private $envelope;

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

        foreach ($filenames as $file) {
            $files[] = file_get_contents($directory . '/' . $file);
        }

        return $files;
    }

    private function _decryptSingleFile(string $file)
    {

        $file = explode('^', $file);
        $content = NULL;
        openssl_open(base64_decode($file[0]), $content, base64_decode($file[1]), $this->privateKey);
        return $content;
    }

    private function _decryptData()
    {
        $files = $this->_getFileContents();

        if (empty($files)) throw new \Error('No files to decode');

        $contents = [];

        foreach ($files as $file) {
            $contents[] = $this->_decryptSingleFile($file);
        }

        usort($contents, function($a, $b) {
            return $a['s'] <=> $b['s'];
        });

        var_dump($contents);

        $result = array_reduce($contents, function($acc, $item) {
            return $acc.json_decode($item, true)['c'];
        }, "");

        return json_decode($result);
    }
}