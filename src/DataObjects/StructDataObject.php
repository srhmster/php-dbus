<?php

namespace Srhmster\PhpDbus\DataObjects;

/**
 * Struct busctl data object
 */
class StructDataObject extends BusctlDataObject
{
    /**
     * Constructor
     *
     * @param BusctlDataObject|BusctlDataObject[] $value
     */
    public function __construct($value)
    {
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
}