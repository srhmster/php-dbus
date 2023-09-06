<?php

namespace Srhmster\PhpDbus\DataObjects;

use Exception;

/**
 * Map busctl data object
 */
class MapDataObject extends BusctlDataObject
{
    /**
     * Constructor
     *
     * @param BusctlDataObject[][] $value
     * @throws Exception
     */
    public function __construct($value)
    {
        if (!$this->isCorrectValues($value)) {
            throw new Exception('Incorrect data object signature inside array');
        }
        
        $firstItem = $value[0];
        if (!$this->isBasicType($firstItem['key'])) {
            throw new Exception(
                'Incorrect key value. The key must be a base data type.'
            );
        }
        
        $keySignature = $firstItem['key']->getSignature();
        $valueSignature = $firstItem['value']->getSignature();
        
        $this->signature = 'a{' . $keySignature . $valueSignature . '}';
        $this->value = $value;
    }
    
    /**
     * @inheritDoc
     */
    public function getValue($withSignature = false)
    {
        $value = count($this->value) . ' ';
        foreach ($this->value as $item) {
            $value .= $item['key']->getValue() . ' '
                . $item['value']->getValue() . ' ';
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
     * Check signature for each element of data objects
     *
     * @param BusctlDataObject[][] $dataObjects
     * @return bool
     */
    private function isCorrectValues($dataObjects)
    {
        $firstItem = array_shift($dataObjects);
        $keySignature = $firstItem['key']->getSignature();
        $valueSignature = $firstItem['value']->getSignature();
        
        foreach ($dataObjects as $dataObject) {
            if ($dataObject['key']->getSignature() !== $keySignature
                || $dataObject['value']->getSignature() !== $valueSignature
            ) {
                return false;
            }
        }
        
        return true;
    }
}