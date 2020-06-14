<?php

namespace Alohomora\model\Chunk;

class ChunkFactory
{
    const CHUNK_MAX_SIZE = 48;

    public function __construct()
    {

    }

    /**
     * @param string $part
     * @param int $start
     * @param int $end
     * @return array
     */
    private function _generateChunk(string $part, int $start, int $end)
    {
        $chunk = [
            's'=>$start,
            'e'=>$end,
            'c'=>$part
        ];

        return $chunk;
    }

    /**
     * @param string $string
     * @return array
     */
    public function splitStringToChunks(string $string)
    {
        return str_split($string, self::CHUNK_MAX_SIZE);
    }
}