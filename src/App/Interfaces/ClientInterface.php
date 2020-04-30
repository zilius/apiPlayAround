<?php

declare(strict_types=1);

namespace Src\App\Interfaces;

/**
 * Base client interface for future integrations of various API's
 * Interface ClientInterface
 * @package Src\App\Interfaces
 */
interface ClientInterface
{
    /**
     * @param string $path
     * @param array $params
     * @return array
     */
    public function get(string $path, array $params): array;

    /**
     * @param string $path
     * @param array $params
     * @return array
     */
    public function post(string $path, array $params): array;
}