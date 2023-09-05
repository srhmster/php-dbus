<?php

namespace Srhmster\PhpDbus\DataObjects;

use Srhmster\PhpDbus\Marshallers\BusctlMarshaller;

/**
 * Boolean busctl data object
 */
class BooleanDataObject extends BusctlDataObject
{
    /**
     * Constructor
     *
     * @param bool $value
     */
    public function __construct($value)
    {
        $this->signature = BusctlMarshaller::BOOL;
        $this->value = $value;
    }
    
    /**
     * @inheritDoc
     */
    public function get($useSignature = false)
    {
        $value = $this->value === true ? 'true' : 'false';
        
        return $useSignature === true
            ? $this->signature . ' ' . $value
            : $value;
    }
}