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
     * @return string|null
     */
    abstract public function getValue($withSignature = false);
    
    /**
     * Create string data object
     *
     * @param string|null $value
     * @return StringDataObject
     */
    public static function s($value = null)
    {
        return new StringDataObject($value);
    }
    
    /**
     * Create object path data object
     *
     * @param string|null $value
     * @return ObjectPathDataObject
     */
    public static function o($value = null)
    {
        return new ObjectPathDataObject($value);
    }
    
    /**
     * Create boolean data object
     *
     * @param bool|null $value
     * @return BooleanDataObject
     */
    public static function b($value = null)
    {
        return new BooleanDataObject($value);
    }
    
    /**
     * Create byte data object
     *
     * @param int|null $value
     * @return NumericDataObject
     */
    public static function y($value = null)
    {
        return new NumericDataObject(BusctlMarshaller::BYTE, $value);
    }
    
    /**
     * Create int16 data object
     *
     * @param int|null $value
     * @return NumericDataObject
     */
    public static function n($value = null)
    {
        return new NumericDataObject(BusctlMarshaller::INT16, $value);
    }
    
    /**
     * Create uint16 data object
     *
     * @param int|null $value
     * @return NumericDataObject
     */
    public static function q($value = null)
    {
        return new NumericDataObject(BusctlMarshaller::UINT16, $value);
    }
    
    /**
     * Create int32 data object
     *
     * @param int|null $value
     * @return NumericDataObject
     */
    public static function i($value = null)
    {
        return new NumericDataObject(BusctlMarshaller::INT32, $value);
    }
    
    /**
     * Create uint32 data object
     *
     * @param int|null $value
     * @return NumericDataObject
     */
    public static function u($value = null)
    {
        return new NumericDataObject(BusctlMarshaller::UINT32, $value);
    }
    
    /**
     * Create int64 data object
     *
     * @param int|null $value
     * @return NumericDataObject
     */
    public static function x($value = null)
    {
        return new NumericDataObject(BusctlMarshaller::INT64, $value);
    }
    
    /**
     * Create uint64 data object
     *
     * @param int|null $value
     * @return NumericDataObject
     */
    public static function t($value = null)
    {
        return new NumericDataObject(BusctlMarshaller::UINT64, $value);
    }
    
    /**
     * Create double data object
     *
     * @param float|null $value
     * @return NumericDataObject
     */
    public static function d($value = null)
    {
        return new NumericDataObject(BusctlMarshaller::DOUBLE, $value);
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
        return new ArrayDataObject($value);
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
        return new MapDataObject($value);
    }
}