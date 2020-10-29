<?php
namespace Alohomora\model\File;

class FileFactory
{
    /** @var string  */
    private $outputPath;

    /** @var string  */
    private $fileName;

    /**
     * FileFactory constructor.
     * @param string $outputPath
     * @param string $fileName
     */
    public function __construct(string $outputPath, string $fileName)
    {
        $this->outputPath = $outputPath;
        $this->fileName = $fileName;
    }

    /**
     * @param array $chunks
     *
     * @return int
     */
    public function createFilesFromChunks(array $chunks): int
    {
        $directory = $this->getDirectoryForEncryptedFiles();

        foreach ($chunks as $key => $chunk) {
            file_put_contents($this->outputPath . '/' . $directory . '/' . $this->getUniqueFileName(), $chunk['c'] . "^" . $chunk['e']);
        }

        return sizeof($chunks);
    }

    /**
     * @return string
     */
    private function getDirectoryForEncryptedFiles(): string
    {
        if (!empty($this->outputPath)) {
            if(!is_dir($this->outputPath)) mkdir($this->outputPath);
        }

        $id = md5($this->fileName);

        if (is_dir($this->outputPath . '/' . $id)) {
            $this->rrmdir($this->outputPath . '/' . $id);
        }

        mkdir($this->outputPath . '/' . $id);

        return $id;
    }

    /**
     * An external function for removing dir with it's all content
     * @param $dir
     *
     * @return void
     */
    private function rrmdir($dir): void
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);

            foreach ($objects as $object) {

                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir") $this->rrmdir($dir . "/" . $object);
                    else unlink($dir . "/" . $object);
                }

            }

            reset($objects);
            rmdir($dir);
        }
    }

    /**
     * @return string
     */
    private function getUniqueFileName(): string
    {
        return md5(uniqid());
    }
}
