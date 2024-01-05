<?php

namespace App\Responses;

class Response
{
    /**
     * @var array
     */
    protected array $data = [];

    /**
     * @var int
     */
    protected int $statusCode = 200;

    /**
     * @return static
     */
    public static function make(): static
    {
        return new static();
    }

    /**
     * @param $data
     * @return $this
     */
    public function json($data): static
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param $statusCode
     * @return $this
     */
    public function status($statusCode): static
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @return void
     */
    public function send(): void
    {
        header('Content-Type: application/json', true, $this->statusCode);
        echo json_encode($this->data);
    }
}