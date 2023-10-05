<?php

namespace Srhmster\PhpDbus\DataObjects;

use InvalidArgumentException;

/**
 * Array busctl data object
 */
class ArrayDataObject extends BusctlDataObject
{
    const SIGNATURE = 'a';
    
    /**
     * Constructor
     *
     * @param BusctlDataObject[] $value
     * @throws InvalidArgumentException
     */
    public function __construct($value)
    {
        $errorMessage = '';
        if (!$this->validate($value, $errorMessage)) {
            throw new InvalidArgumentException($errorMessage);
        }
        
        $this->signature = self::SIGNATURE . $value[0]->getSignature();
        $this->value = $value;
    }
    
    /**
     * @inheritDoc
     */
    public function getValue($withSignature = false)
    {
        if (count($this->value) === 1
            && ($this->value[0]->getValue() === null
                || $this->value[0]->getValue() === '0'
            )
        ) {
            $value = '0';
        } else {
            $value = count($this->value) . ' ';
            foreach ($this->value as $dataObject) {
                $value .= $dataObject->getValue() . ' ';
            }
        }
        
        return $withSignature === true
            ? $this->signature . ' ' . $value
            : $value;
    }
    
    /**
     * Check the correctness of the specified value
     *
     * @param BusctlDataObject[] $value
     * @param string $message
     * @return bool
     */
    private function validate($value, &$message)
    {
        if (count($value) === 0) {
            $message = 'The value cannot be an empty array';
            
            return false;
        }
    
        $signature = array_shift($value)->getSignature();
        foreach ($value as $item) {
            if ($item->getSignature() !== $signature) {
                $message = 'The value cannot be an array of elements with '
                    . 'different signatures';
                
                return false;
            }
        }
        
        return true;
    }
}