<?php

namespace Srhmster\PhpDbus\Marshallers;

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
     */
    public function marshal($data);
    
    /**
     * Convert Dbus data to PHP format
     *
     * @param string $signature Description of the data structure
     * @param array $data
     * @return array|string|int|float|bool|null
     */
    public function unmarshal($signature, &$data);
}
