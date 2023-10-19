<?php

declare(strict_types=1);

namespace Srhmster\PhpDbus\DataObjects;

/**
 * Boolean busctl data object
 */
class BooleanDataObject extends BusctlDataObject
{
    const SIGNATURE = 'b';
    
    /**
     * Constructor
     *
     * @param bool|null $value
     */
    public function __construct(?bool $value)
    {
        $this->signature = self::SIGNATURE;
        $this->value = $value;
    }
    
    /**
     * @inheritDoc
     */
    public function getValue(bool $withSignature = false): ?string
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