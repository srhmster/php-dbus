<?php

declare(strict_types=1);

namespace Srhmster\PhpDbus\DataObjects;

/**
 * Numeric busctl data object
 */
class NumericDataObject extends BusctlDataObject
{
    /**
     * Constructor
     *
     * @param NumericSignature $signature
     * @param int|float|null $value
     */
    public function __construct(
        NumericSignature $signature,
        int|float|null $value = null
    ) {
        $this->signature = $signature->value;
        $this->value = $value;
    }
    
    /**
     * @inheritDoc
     */
    public function getValue(bool $withSignature = false): ?string
    {
        return $withSignature === true
            ? $this->signature . ' ' . $this->value
            : (string)$this->value;
    }
}