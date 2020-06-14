<?php

namespace Alohomora;

class Alohomora
{
    private $entry;
    private $publicKey;
    private $privateKey;
    private $outputPath;

    public function __construct()
    {
    }

    public function setEntry(array $entry)
    {
        $this->entry = $entry;
    }

    public function setPublicKey(string $key)
    {
        $this->publicKey = $key;
    }

    public function setPrivateKey(string $key)
    {
        $this->privateKey = $key;
    }

    public function encryptEntry()
    {
        $encrypted = "";
        return $encrypted;
    }

    public function setOutputPath(string $output)
    {
        $this->outputPath = $output;
    }
}