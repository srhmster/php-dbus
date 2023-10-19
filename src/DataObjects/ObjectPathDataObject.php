<?php

declare(strict_types=1);

namespace Srhmster\PhpDbus\DataObjects;

use TypeError;

/**
 * Object path busctl data object
 */
class ObjectPathDataObject extends BusctlDataObject
{
    const SIGNATURE = 'o';
    
    /**
     * Constructor
     *
     * @param string|null $value
     * @throws TypeError
     */
    public function __construct(?string $value)
    {
        if (!is_null($value)
            && !preg_match('/^(\/|(\/[a-zA-Z0-9_]+)+)$/', $value)
        ) {
            throw new TypeError('Invalid path to object specified');
        }
        
        $this->signature = self::SIGNATURE;
        $this->value = $value;
    }
    
    /**
     * @inheritDoc
     */
    public function getValue(bool $withSignature = false): ?string
    {
        return $withSignature === true
            ? $this->signature . ' ' . $this->value
            : $this->value;
    }
}