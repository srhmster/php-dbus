<?php

declare(strict_types=1);

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
    public function getValue(bool $withSignature = false): ?string
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