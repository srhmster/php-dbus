<?php

namespace Srhmster\PhpDbus\DataObjects;

use InvalidArgumentException;

/**
 * Map busctl data object
 */
class MapDataObject extends BusctlDataObject
{
    /**
     * Constructor
     *
     * @param BusctlDataObject[][] $value
     * @throws InvalidArgumentException
     */
    public function __construct($value)
    {
        $errorMessage = '';
        if (!$this->validate($value, $errorMessage)) {
            throw new InvalidArgumentException($errorMessage);
        }
        
        $keySignature = $value[0]['key']->getSignature();
        $valueSignature = $value[0]['value']->getSignature();
        
        $this->signature = ArrayDataObject::SIGNATURE . '{' . $keySignature
            . $valueSignature . '}';
        $this->value = $value;
    }
    
    /**
     * @inheritDoc
     */
    public function getValue($withSignature = false)
    {
        if (count($this->value) === 1
            && ($this->value[0]['value']->getValue() === null
                || $this->value[0]['value']->getValue() === '0'
            )
        ) {
            $value = '0';
        } else {
            $value = count($this->value) . ' ';
            foreach ($this->value as $item) {
                $value .= $item['key']->getValue() . ' '
                    . $item['value']->getValue() . ' ';
            }
        }
        
        return $withSignature === true
            ? $this->signature . ' ' . $value
            : $value;
    }
    
    /**
     * Check if a data object is a base type data object
     *
     * @param BusctlDataObject $dataObject
     * @return bool
     */
    private function isBasicType($dataObject)
    {
        if ($dataObject instanceof ArrayDataObject
            || $dataObject instanceof MapDataObject
            || $dataObject instanceof StructDataObject
            || $dataObject instanceof VariantDataObject
        ) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Check the correctness of the specified item structure and value
     *
     * @param BusctlDataObject[] $item
     * @return bool
     */
    private function validateItem($item)
    {
        return isset($item['key'])
            && isset($item['value'])
            && $this->isBasicType($item['key'])
            && $this->isBasicType($item['value']);
    }
    
    /**
     * Check the correctness of the specified value
     *
     * @param BusctlDataObject[][] $value
     * @param string $message
     * @return bool
     */
    private function validate($value, &$message)
    {
        if (count($value) === 0) {
            $message = 'The value cannot be an empty array';
            
            return false;
        }
        
        $firstItem = array_shift($value);
        if (!$this->validateItem($firstItem)) {
            $message = 'Each element must contain a "key" and a "value" element,'
                . ' which are BusctlDataObject::class objects of basic data types';
            
            return false;
        }
        
        $keySignature = $firstItem['key']->getSignature();
        $valueSignature = $firstItem['value']->getSignature();
        foreach ($value as $item) {
            if (!$this->validateItem($item)) {
                $message = 'Each element must contain a "key" and a "value" element,'
                    . ' which are BusctlDataObject::class objects of basic data types';
                
                return false;
            }
            
            if ($item['key']->getSignature() !== $keySignature
                || $item['value']->getSignature() !== $valueSignature
            ) {
                $message = 'Each element must have the same data types for the '
                    . '"key" and "value" elements';
                
                return false;
            }
        }
        
        return true;
    }
}