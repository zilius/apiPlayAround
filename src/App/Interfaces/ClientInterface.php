<?php

declare(strict_types=1);

namespace Src\App\Interfaces;

interface ClientInterface
{
    public function get(string $path, array $params): array;

    public function post(string $path, array $params): array;
}