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
     * @return string
     */
    private function _generateChunk(string $part, int $start, int $end)
    {
        $chunk = [
            's' => $start,
            'e' => $end,
            'c' => $part
        ];

        return json_encode($chunk);
    }

    /**
     * @param string $string
     * @return array
     */
    public function splitStringToChunks(string $string)
    {
        $chunks = [];
        $baseChunks = str_split($string, self::CHUNK_MAX_SIZE);

        foreach ($baseChunks as $key => $chunk) {
            $start = $key*self::CHUNK_MAX_SIZE;
            $end = (($key*self::CHUNK_MAX_SIZE)+self::CHUNK_MAX_SIZE);

            if(strlen($chunk)<48) $end = strlen($chunk) + $start;
            $chunks[] = $this->_generateChunk($chunk, $start,$end-1);
        }

        return $chunks;
    }
}