<?php

declare(strict_types=1);

namespace Srhmster\PhpDbus\Marshallers;

use TypeError;

/**
 * Data converter interface
 */
interface Marshaller
{
    /**
     * Convert PHP data to Dbus format
     *
     * @param mixed $data
     * @return string|null
     * @throws TypeError
     */
    public function marshal(mixed $data): ?string;
    
    /**
     * Convert Dbus data to PHP format
     *
     * @param string|array $data
     * @param string|null $signature Description of the data structure
     * @return array|string|int|float|bool|null
     */
    public function unmarshal(
        string|array &$data,
        ?string $signature = null
    ): array|string|int|float|bool|null;
}
