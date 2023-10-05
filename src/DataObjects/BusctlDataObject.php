<?php

namespace Srhmster\PhpDbus\DataObjects;

use InvalidArgumentException;

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
     * @throws InvalidArgumentException
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
     * @throws InvalidArgumentException
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
     * @throws InvalidArgumentException
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
     * @throws InvalidArgumentException
     */
    public static function y($value = null)
    {
        return new NumericDataObject(NumericDataObject::BYTE_SIGNATURE, $value);
    }
    
    /**
     * Create int16 data object
     *
     * @param int|null $value
     * @return NumericDataObject
     * @throws InvalidArgumentException
     */
    public static function n($value = null)
    {
        return new NumericDataObject(NumericDataObject::INT16_SIGNATURE, $value);
    }
    
    /**
     * Create uint16 data object
     *
     * @param int|null $value
     * @return NumericDataObject
     * @throws InvalidArgumentException
     */
    public static function q($value = null)
    {
        return new NumericDataObject(NumericDataObject::UINT16_SIGNATURE, $value);
    }
    
    /**
     * Create int32 data object
     *
     * @param int|null $value
     * @return NumericDataObject
     * @throws InvalidArgumentException
     */
    public static function i($value = null)
    {
        return new NumericDataObject(NumericDataObject::INT32_SIGNATURE, $value);
    }
    
    /**
     * Create uint32 data object
     *
     * @param int|null $value
     * @return NumericDataObject
     * @throws InvalidArgumentException
     */
    public static function u($value = null)
    {
        return new NumericDataObject(NumericDataObject::UINT32_SIGNATURE, $value);
    }
    
    /**
     * Create int64 data object
     *
     * @param int|null $value
     * @return NumericDataObject
     * @throws InvalidArgumentException
     */
    public static function x($value = null)
    {
        return new NumericDataObject(NumericDataObject::INT64_SIGNATURE, $value);
    }
    
    /**
     * Create uint64 data object
     *
     * @param int|null $value
     * @return NumericDataObject
     * @throws InvalidArgumentException
     */
    public static function t($value = null)
    {
        return new NumericDataObject(NumericDataObject::UINT64_SIGNATURE, $value);
    }
    
    /**
     * Create double data object
     *
     * @param float|null $value
     * @return NumericDataObject
     * @throws InvalidArgumentException
     */
    public static function d($value = null)
    {
        return new NumericDataObject(NumericDataObject::DOUBLE_SIGNATURE, $value);
    }
    
    /**
     * Create variant data object
     *
     * @param BusctlDataObject $value
     * @return VariantDataObject
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
     * @throws InvalidArgumentException
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
     * @throws InvalidArgumentException
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
     * @throws InvalidArgumentException
     */
    public static function e($value)
    {
        return new MapDataObject($value);
    }
}