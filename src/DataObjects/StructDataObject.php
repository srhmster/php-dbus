<?php

declare(strict_types=1);

namespace Srhmster\PhpDbus\DataObjects;

use TypeError;

/**
 * Struct busctl data object
 */
class StructDataObject extends BusctlDataObject
{
    /**
     * Constructor
     *
     * @param BusctlDataObject|BusctlDataObject[] $value
     * @throws TypeError
     */
    public function __construct(BusctlDataObject|array $value)
    {
        $errorMessage = '';
        if (!$this->validate($value, $errorMessage)) {
            throw new TypeError($errorMessage);
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
    public function getValue(bool $withSignature = false): ?string
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
            $value = trim($value);
            
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
     * @param string $message
     * @return bool
     */
    private function validate(
        BusctlDataObject|array $value,
        string &$message
    ): bool
    {
        if (is_array($value)) {
            if (count($value) === 0) {
                $message = 'The value cannot be an empty array';
                
                return false;
            }
            
            foreach ($value as $item) {
                if (!($item instanceof BusctlDataObject)) {
                    $message = 'A BusctlDataObject::class value item was expected, '
                        . 'but a ' . gettype($item) . ' was passed';
                    
                    return false;
                }
            }
        } else {
            if (!($value instanceof BusctlDataObject)) {
                $message = 'A BusctlDataObject::class value was expected, but a '
                    . gettype($value) . ' was passed';
                
                return false;
            }
        }
        
        return true;
    }
}