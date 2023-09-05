<?php

namespace Srhmster\PhpDbus\DataObjects;

use Srhmster\PhpDbus\Marshallers\BusctlMarshaller;

/**
 * String busctl data object
 */
class StringDataObject extends BusctlDataObject
{
    /**
     * Constructor
     *
     * @param string $value
     */
    public function __construct($value)
    {
        $this->signature = BusctlMarshaller::STRING;
        $this->value = $value;
    }
    
    /**
     * @inheritDoc
     */
    public function getValue($withSignature = false)
    {
        return $withSignature === true
            ? $this->signature . ' "' . $this->value . '"'
            : '"' . $this->value . '"';
    }
}