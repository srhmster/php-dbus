<?php

namespace Srhmster\PhpDbus\DataObjects;

use InvalidArgumentException;

/**
 * Struct busctl data object
 */
class StructDataObject extends BusctlDataObject
{
    /**
     * Constructor
     *
     * @param BusctlDataObject|BusctlDataObject[] $value
     * @throws InvalidArgumentException
     */
    public function __construct($value)
    {
        if (!$this->validate($value)) {
            throw new InvalidArgumentException(
                'The value cannot be an empty array'
            );
        }
        
        if ($value instanceof BusctlDataObject) {
            $signature = $value->getSignature();
        } else {
            $signature = '';
            foreach ($value as $dataObject) {
                $signature .= $dataObject->getSignature();
            }
        }
        
        $this->signature = '(' . $signature . ')';
        $this->value = $value;
    }
    
    /**
     * @inheritDoc
     */
    public function getValue($withSignature = false)
    {
        if ($this->value instanceof BusctlDataObject) {
            $value = $this->value->getValue();
        } else {
            $value = '';
            foreach ($this->value as $dataObject) {
                $value .= $dataObject->getValue() === null
                    ? ''
                    : $dataObject->getValue() . ' ';
            }
            
            if ($value === '') {
                $value = null;
            }
        }
        
        return $withSignature === true
            ? $this->signature . ' ' . $value
            : $value;
    }
    
    /**
     * Check the correctness of the specified value
     *
     * @param BusctlDataObject|BusctlDataObject[] $value
     * @return bool
     */
    private function validate($value)
    {
        if (is_array($value) && count($value) === 0) {
            return false;
        }
        
        return true;
    }
}