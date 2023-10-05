<?php

namespace Srhmster\PhpDbus\DataObjects;

use InvalidArgumentException;

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
     * @throws InvalidArgumentException
     */
    public function __construct($value = null)
    {
        if (!$this->validate($value)) {
            throw new InvalidArgumentException(
                'A string or null value was expected, but a ' . gettype($value)
                . ' was passed'
            );
        }
        
        $this->signature = self::SIGNATURE;
        $this->value = $value;
    }
    
    /**
     * @inheritDoc
     */
    public function getValue($withSignature = false)
    {
        $value = $this->value === null
            ? $this->value
            : ('"' . $this->value . '"');
        
        return $withSignature === true
            ? $this->signature . ' ' . $value
            : $value;
    }
    
    /**
     * Check the correctness of the specified value
     *
     * @param mixed $value
     * @return bool
     */
    private function validate($value)
    {
        return is_null($value) || is_string($value);
    }
}