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
     * @param bool|null $value
     */
    public function __construct($value = null)
    {
        $this->signature = BusctlMarshaller::BOOL;
        $this->value = $value;
    }
    
    /**
     * @inheritDoc
     */
    public function getValue($withSignature = false)
    {
        if ($this->value === null) {
            $value = null;
        } else {
            $value = $this->value === true ? 'true' : 'false';
        }
        
        return $withSignature === true
            ? $this->signature . ' ' . $value
            : $value;
    }
}