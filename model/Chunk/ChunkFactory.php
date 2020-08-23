<?php
namespace Alohomora\model\Chunk;

class ChunkFactory
{
    const CHUNK_MAX_SIZE = 48;

    /**
     * ChunkFactory constructor.
     */
    public function __construct()
    {
    }

    /**
     * Splits given string into Chunk(s)
     * Size of the Chunk is specified by CHUNK_MAX_SIZE const
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

            $chunks[] = $this->generateChunk($chunk, $start, $end - 1);
        }

        return $chunks;
    }

    /**
     * Creates a Chunk object by given arguments
     * @param string $part
     * @param int $start
     * @param int $end
     * @return Chunk
     */
    private function generateChunk(string $part, int $start, int $end) : Chunk
    {
        return new Chunk($start, $end, $part);
    }
}
