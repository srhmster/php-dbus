<?php

namespace Srhmster\PhpDbus\Marshallers;

use InvalidArgumentException;

/**
 * Data converter interface
 */
interface Marshaller
{
    /**
     * Convert PHP data to Dbus format
     *
     * @param mixed $data
     * @return string
     * @throws InvalidArgumentException
     */
    public function marshal($data);
    
    /**
     * Convert Dbus data to PHP format
     *
     * @param string $signature Description of the data structure
     * @param array $data
     * @return array|string|int|float|bool|null
     * @throws InvalidArgumentException
     */
    public function unmarshal($signature, &$data);
}
