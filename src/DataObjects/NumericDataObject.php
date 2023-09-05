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
    public function getValue($withSignature = false)
    {
        return $withSignature === true
            ? $this->signature . ' ' . $this->value
            : $this->value;
    }
}