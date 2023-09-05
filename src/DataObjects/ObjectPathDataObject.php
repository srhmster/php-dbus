<?php

namespace Srhmster\PhpDbus\DataObjects;

use Srhmster\PhpDbus\Marshallers\BusctlMarshaller;

/**
 * Object path busctl data object
 */
class ObjectPathDataObject extends BusctlDataObject
{
    /**
     * Constructor
     *
     * @param string $value
     */
    public function __construct($value)
    {
        $this->signature = BusctlMarshaller::OBJECT_PATH;
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