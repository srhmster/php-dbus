<?php

declare(strict_types=1);

namespace Srhmster\PhpDbus\DataObjects;

/**
 * String busctl data object
 */
class StringDataObject extends BusctlDataObject
{
    const SIGNATURE = 's';
    
    /**
     * Constructor
     *
     * @param string|null $value
     */
    public function __construct(?string $value)
    {
        $this->signature = self::SIGNATURE;
        $this->value = $value;
    }
    
    /**
     * @inheritDoc
     */
    public function getValue(bool $withSignature = false): ?string
    {
        $value = $this->value === null
            ? $this->value
            : ('"' . $this->value . '"');
        
        return $withSignature === true
            ? $this->signature . ' ' . $value
            : $value;
    }
}