<?php

declare(strict_types=1);

namespace Srhmster\PhpDbus\DataObjects;

use TypeError;

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
     * @throws TypeError
     */
    public function __construct(array $value)
    {
        $errorMessage = '';
        if (!$this->validate($value, $errorMessage)) {
            throw new TypeError($errorMessage);
        }
        
        $this->signature = self::SIGNATURE . $value[0]->getSignature();
        $this->value = $value;
    }
    
    /**
     * @inheritDoc
     */
    public function getValue(bool $withSignature = false): ?string
    {
        if (count($this->value) === 1
            && ($this->value[0]->getValue() === null
                || $this->value[0]->getValue() === ''
            )
        ) {
            $value = '0';
        } else {
            $value = count($this->value);
            foreach ($this->value as $dataObject) {
                $value .= ' ' . $dataObject->getValue();
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
    private function validate(array $value, string &$message): bool
    {
        if (count($value) === 0) {
            $message = 'The value cannot be an empty array';
            
            return false;
        }
    
        $signature = null;
        foreach ($value as $item) {
            if (!($item instanceof BusctlDataObject)) {
                $message = 'A BusctlDataObject::class value item was expected, '
                    . 'but a ' . gettype($item) . ' was passed';
                
                return false;
            }
            
            if (is_null($signature)) {
                $signature = $item->getSignature();
            } else {
                if ($item->getSignature() !== $signature) {
                    $message = 'The value cannot be an array of elements with '
                        . 'different signatures';
        
                    return false;
                }
            }
        }
        
        return true;
    }
}