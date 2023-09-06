<?php

namespace Srhmster\PhpDbus\DataObjects;

use Exception;
use Srhmster\PhpDbus\Marshallers\BusctlMarshaller;

/**
 * Variant busctl data object
 */
class VariantDataObject extends BusctlDataObject
{
    /**
     * Constructor
     *
     * @param BusctlDataObject $value
     * @throws Exception
     */
    public function __construct(BusctlDataObject $value)
    {
        if ($value instanceof VariantDataObject) {
            throw new Exception('Incorrect value for variant data object');
        }
        
        $this->signature = BusctlMarshaller::VARIANT;
        $this->value = $value;
    }
    
    /**
     * @inheritDoc
     */
    public function getValue($withSignature = false)
    {
        return $withSignature === true
            ? $this->signature . ' ' . $this->value->getValue(true)
            : $this->value->getValue(true);
    }
}