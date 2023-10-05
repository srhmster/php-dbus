<?php

namespace Srhmster\PhpDbus\DataObjects;

use InvalidArgumentException;

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
     * @throws InvalidArgumentException
     */
    public function __construct($value = null)
    {
        if (!$this->validate($value)) {
            throw new InvalidArgumentException(
                'A object path or null was expected, but ' . gettype($value)
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
        return $withSignature === true
            ? $this->signature . ' ' . $this->value
            : $this->value;
    }
    
    /**
     * Check the correctness of the specified value
     *
     * @param mixed $value
     * @return bool
     */
    private function validate($value)
    {
        return is_null($value)
            || (is_string($value)
                && preg_match('/^(\/|(\/[a-zA-Z0-9_]+)+)$/', $value)
            );
    }
}