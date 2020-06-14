<?php

namespace model\File;

class FileFactory
{
    public function __construct()
    {
    }

    public function createFilesFromChunks(array $chunks)
    {
        foreach ($chunks as $key=>$chunk)
        {
            file_put_contents('enc/'.$key, $chunk);
        }

        return sizeof($chunks);
    }
}