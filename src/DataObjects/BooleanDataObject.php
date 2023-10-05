<?php

namespace Srhmster\PhpDbus\DataObjects;

use InvalidArgumentException;

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
     * @throws InvalidArgumentException
     */
    public function __construct($value = null)
    {
        if (!$this->validate($value)) {
            throw new InvalidArgumentException(
                'A boolean or null value was expected, but a ' . gettype($value)
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
        if ($this->value === null) {
            $value = null;
        } else {
            $value = $this->value === true ? 'true' : 'false';
        }
        
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
        return is_null($value) || is_bool($value);
    }
}