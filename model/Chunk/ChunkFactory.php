<?php

namespace Alohomora\model\Chunk;

use PHPUnit\Util\Json;

class ChunkFactory
{
    const CHUNK_MAX_SIZE = 48;

    public function __construct()
    {
    }

    /**
     * @param string $string
     * @return array
     */
    public function splitStringToChunks(string $string): array
    {
        $chunks = [];
        $baseChunks = mb_str_split($string, self::CHUNK_MAX_SIZE);

        foreach ($baseChunks as $key => $chunk) {
            $start = $key * self::CHUNK_MAX_SIZE;
            $end = (($key * self::CHUNK_MAX_SIZE) + self::CHUNK_MAX_SIZE);

            if (strlen($chunk) < self::CHUNK_MAX_SIZE) $end = strlen($chunk) + $start;
            $chunks[] = $this->_generateChunk($chunk, $start, $end - 1);
        }

        return $chunks;
    }

    /**
     * @param string $part
     * @param int $start
     * @param int $end
     * @return Chunk
     */
    private function _generateChunk(string $part, int $start, int $end) : Chunk
    {
        return new Chunk($start, $end, $part);
    }
}