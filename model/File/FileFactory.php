<?php

namespace Alohomora\model\File;

class FileFactory
{
    private $outputPath;
    private $fileName;

    public function __construct(string $outputPath, string $fileName)
    {
        $this->outputPath = $outputPath;
        $this->fileName = $fileName;
    }

    public function createFilesFromChunks(array $chunks)
    {
        $directory = $this->_getDirectoryForEncryptedFiles();
        foreach ($chunks as $key => $chunk) {
            file_put_contents($this->outputPath . '/' . $directory . '/' . $this->_getFileName(), $chunk['c'] . "^" . $chunk['e']);
        }

        return sizeof($chunks);
    }

    private function _getDirectoryForEncryptedFiles()
    {
        $id = md5($this->fileName);
        if (is_dir($this->outputPath . '/' . $id)) {
            $this->rrmdir($this->outputPath . '/' . $id);
        }
        mkdir($this->outputPath . '/' . $id);
        return $id;
    }

    private function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir") rrmdir($dir . "/" . $object); else unlink($dir . "/" . $object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    private function _getFileName()
    {
        return md5(uniqid());
    }
}