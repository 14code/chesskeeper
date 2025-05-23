<?php
// src/Services/MessageStack.php

namespace Chesskeeper\Services;

class MessageStack
{
    private string $file;

    public function __construct(int $userId = 1)
    {
        $this->file = __DIR__ . '/../../data/messages.' . $userId . '.json';
    }

    public function push(string $type, string $text): void
    {
        $messages = $this->getAll();
        $messages[] = ['type' => $type, 'text' => $text];
        file_put_contents($this->file, json_encode($messages));
    }

    public function getAll(): array
    {
        if (!file_exists($this->file)) return [];
        return json_decode(file_get_contents($this->file), true) ?? [];
    }

    public function clear(): void
    {
        if (file_exists($this->file)) unlink($this->file);
    }

    public function popAll(): array
    {
        $messages = $this->getAll();
        $this->clear();
        return $messages;
    }
}
