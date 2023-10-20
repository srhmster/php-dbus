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
     * @param string $signature Description of the data structure
     * @param array $data
     * @return array|string|int|float|bool|null
     */
    public function unmarshal(
        string $signature,
        array &$data
    ): array|string|int|float|bool|null;
    
    /**
     * Prepare data for the unmarshalling process
     *
     * @param mixed $data
     * @return array
     */
    public function unmarshallingPrepare(mixed $data): array;
}
