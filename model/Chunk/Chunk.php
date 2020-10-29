<?php
namespace Alohomora\model\Chunk;

class Chunk
{
    /** @var int  */
    private $start;

    /** @var int  */
    private $end;

    /** @var string  */
    private $content;

    /**
     * Chunk constructor.
     * @param int $start
     * @param int $end
     * @param string $content
     */
    public function __construct(int $start, int $end, string $content)
    {
        $this->start = $start;
        $this->end = $end;
        $this->content = $content;
    }

    /**
     * @return int
     */
    public function getStart(): int
    {
        return $this->start;
    }

    /**
     * @param int $start
     *
     * @return void
     */
    public function setStart(int $start): void
    {
        $this->start = $start;
    }

    /**
     * @return int
     */
    public function getEnd(): int
    {
        return $this->end;
    }

    /**
     * @param int $end
     *
     * @return void
     */
    public function setEnd(int $end): void
    {
        $this->end = $end;
    }

    /**
     * @return string
     *
     * @return void
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return void
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * Returns a chunk in JSON format
     * @return string
     */
    public function getJSONStringifiedChunk(): string
    {
        $chunk = [
            's' => $this->start,
            'e' => $this->end,
            'c' => $this->content
        ];

        return json_encode($chunk);
    }

}
