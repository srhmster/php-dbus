<?php

namespace Srhmster\PhpDbus\DataObjects;

use Exception;
use Srhmster\PhpDbus\Marshallers\BusctlMarshaller;

/**
 * Array busctl data object
 */
class ArrayDataObject extends BusctlDataObject
{
    /**
     * Constructor
     *
     * @param BusctlDataObject[] $value
     * @throws Exception
     */
    public function __construct($value)
    {
        if (!$this->isCorrectValues($value)) {
            throw new Exception('Incorrect data object signature inside array');
        }
        
        $this->signature = BusctlMarshaller::ARR . $value[0]->getSignature();
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
     * Check signature for each element of data objects
     *
     * @param BusctlDataObject[] $dataObjects
     * @return bool
     */
    private function isCorrectValues($dataObjects)
    {
        $signature = array_shift($dataObjects)->getSignature();
        
        foreach ($dataObjects as $dataObject) {
            if ($dataObject->getSignature() !== $signature) {
                return false;
            }
        }
        
        return true;
    }
}