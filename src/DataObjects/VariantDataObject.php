<?php

namespace Srhmster\PhpDbus\DataObjects;

/**
 * Variant busctl data object
 */
class VariantDataObject extends BusctlDataObject
{
    const SIGNATURE = 'v';
    
    /**
     * Constructor
     *
     * @param BusctlDataObject $value
     */
    public function __construct(BusctlDataObject $value)
    {
        $this->signature = self::SIGNATURE;
        $this->value = $value;
    }
    
    /**
     * @inheritDoc
     */
    public function getValue($withSignature = false)
    {
        if ($this->value->getValue() === null) {
            $value = null;
        } else {
            $value = $this->value->getValue(true);
        }
        
        return $withSignature === true
            ? $this->signature . ' ' . $value
            : $value;
    }
}