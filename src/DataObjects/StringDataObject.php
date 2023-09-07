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
     * @param string|null $value
     */
    public function __construct($value = null)
    {
        $this->signature = BusctlMarshaller::STRING;
        $this->value = $value;
    }
    
    /**
     * @inheritDoc
     */
    public function getValue($withSignature = false)
    {
        $value = $this->value === null
            ? $this->value
            : ('"' . $this->value . '"');
        
        return $withSignature === true
            ? $this->signature . ' ' . $value
            : $value;
    }
}