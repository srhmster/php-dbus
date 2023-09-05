<?php

namespace Srhmster\PhpDbus\DataObjects;

/**
 * Numeric busctl data object
 */
class NumericDataObject extends BusctlDataObject
{
    /**
     * Constructor
     *
     * @param string $signature
     * @param int|float $value
     */
    public function __construct($signature, $value)
    {
        $this->signature = $signature;
        $this->value = $value;
    }
    
    /**
     * @inheritDoc
     */
    public function get($useSignature = false)
    {
        return $useSignature === true
            ? $this->signature . ' ' . $this->value
            : $this->value;
    }
}