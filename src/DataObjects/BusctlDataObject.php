<?php

namespace Srhmster\PhpDbus\DataObjects;

use Exception;
use Srhmster\PhpDbus\Marshallers\BusctlMarshaller;

/**
 * Busctl data object
 */
abstract class BusctlDataObject
{
    /**
     * Data type
     *
     * @var string
     */
    protected $signature;
    
    /**
     * Data value
     *
     * @var mixed
     */
    protected $value;
    
    /**
     * Get data object signature
     *
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
    }
    
    /**
     * Get data object value
     *
     * @param bool $withSignature
     * @return mixed
     */
    abstract public function getValue($withSignature = false);
    
    /**
     * Create string data object
     *
     * @param string $value
     * @return StringDataObject
     */
    public static function s($value)
    {
        return new StringDataObject($value);
    }
    
    /**
     * Create object path data object
     *
     * @param string $value
     * @return ObjectPathDataObject
     */
    public static function o($value)
    {
        return new ObjectPathDataObject($value);
    }
    
    /**
     * Create boolean data object
     *
     * @param bool $value
     * @return BooleanDataObject
     */
    public static function b($value)
    {
        return new BooleanDataObject($value);
    }
    
    /**
     * Create byte data object
     *
     * @param int $data
     * @return NumericDataObject
     */
    public static function y($data)
    {
        return new NumericDataObject(BusctlMarshaller::BYTE, $data);
    }
    
    /**
     * Create int16 data object
     *
     * @param int $data
     * @return NumericDataObject
     */
    public static function n($data)
    {
        return new NumericDataObject(BusctlMarshaller::INT16, $data);
    }
    
    /**
     * Create uint16 data object
     *
     * @param int $data
     * @return NumericDataObject
     */
    public static function q($data)
    {
        return new NumericDataObject(BusctlMarshaller::UINT16, $data);
    }
    
    /**
     * Create int32 data object
     *
     * @param int $data
     * @return NumericDataObject
     */
    public static function i($data)
    {
        return new NumericDataObject(BusctlMarshaller::INT32, $data);
    }
    
    /**
     * Create uint32 data object
     *
     * @param int $data
     * @return NumericDataObject
     */
    public static function u($data)
    {
        return new NumericDataObject(BusctlMarshaller::UINT32, $data);
    }
    
    /**
     * Create int64 data object
     *
     * @param int $data
     * @return NumericDataObject
     */
    public static function x($data)
    {
        return new NumericDataObject(BusctlMarshaller::INT64, $data);
    }
    
    /**
     * Create uint64 data object
     *
     * @param int $data
     * @return NumericDataObject
     */
    public static function t($data)
    {
        return new NumericDataObject(BusctlMarshaller::UINT64, $data);
    }
    
    /**
     * Create double data object
     *
     * @param float $data
     * @return NumericDataObject
     */
    public static function d($data)
    {
        return new NumericDataObject(BusctlMarshaller::DOUBLE, $data);
    }
    
    /**
     * Create variant data object
     *
     * @param BusctlDataObject $value
     * @return VariantDataObject
     * @throws Exception
     */
    public static function v(BusctlDataObject $value)
    {
        return new VariantDataObject($value);
    }
    
    /**
     * Create struct data object
     *
     * @param BusctlDataObject|BusctlDataObject[] $value
     * @return StructDataObject
     */
    public static function r($value)
    {
        return new StructDataObject($value);
    }
    
    /**
     * Create array data object
     *
     * @param BusctlDataObject[] $value
     * @return ArrayDataObject
     * @throws Exception
     */
    public static function a($value)
    {
        return new ArrayDataObject(BusctlMarshaller::ARR, $value);
    }
    
    /**
     * Create map data object
     *
     * @param BusctlDataObject[][] $value
     * @return MapDataObject
     * @throws Exception
     */
    public static function e($value)
    {
        return new MapDataObject(BusctlMarshaller::ARR, $value);
    }
}