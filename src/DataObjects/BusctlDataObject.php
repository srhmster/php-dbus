<?php

declare(strict_types=1);

namespace Srhmster\PhpDbus\DataObjects;

use TypeError;

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
    protected string $signature;
    
    /**
     * Data value
     *
     * @var mixed
     */
    protected mixed $value;
    
    /**
     * Get data object signature
     *
     * @return string
     */
    public function getSignature(): string
    {
        return $this->signature;
    }
    
    /**
     * Get data object value
     *
     * @param bool $withSignature
     * @return string|null
     */
    abstract public function getValue(bool $withSignature = false): ?string;
    
    /**
     * Create string data object
     *
     * @param string|null $value
     * @return StringDataObject
     */
    public static function s(?string $value = null): StringDataObject
    {
        return new StringDataObject($value);
    }
    
    /**
     * Create object path data object
     *
     * @param string|null $value
     * @return ObjectPathDataObject
     * @throws TypeError
     */
    public static function o(?string $value = null): ObjectPathDataObject
    {
        return new ObjectPathDataObject($value);
    }
    
    /**
     * Create boolean data object
     *
     * @param bool|null $value
     * @return BooleanDataObject
     */
    public static function b(?bool $value = null): BooleanDataObject
    {
        return new BooleanDataObject($value);
    }
    
    /**
     * Create byte data object
     *
     * @param int|null $value
     * @return NumericDataObject
     */
    public static function y(?int $value = null): NumericDataObject
    {
        return new NumericDataObject(NumericSignature::Byte, $value);
    }
    
    /**
     * Create int16 data object
     *
     * @param int|null $value
     * @return NumericDataObject
     */
    public static function n(?int $value = null): NumericDataObject
    {
        return new NumericDataObject(NumericSignature::Int16, $value);
    }
    
    /**
     * Create uint16 data object
     *
     * @param int|null $value
     * @return NumericDataObject
     */
    public static function q(?int $value = null): NumericDataObject
    {
        return new NumericDataObject(NumericSignature::UInt16, $value);
    }
    
    /**
     * Create int32 data object
     *
     * @param int|null $value
     * @return NumericDataObject
     */
    public static function i(?int $value = null): NumericDataObject
    {
        return new NumericDataObject(NumericSignature::Int32, $value);
    }
    
    /**
     * Create uint32 data object
     *
     * @param int|null $value
     * @return NumericDataObject
     */
    public static function u(?int $value = null): NumericDataObject
    {
        return new NumericDataObject(NumericSignature::UInt32, $value);
    }
    
    /**
     * Create int64 data object
     *
     * @param int|null $value
     * @return NumericDataObject
     */
    public static function x(?int $value = null): NumericDataObject
    {
        return new NumericDataObject(NumericSignature::Int64, $value);
    }
    
    /**
     * Create uint64 data object
     *
     * @param int|null $value
     * @return NumericDataObject
     */
    public static function t(?int $value = null): NumericDataObject
    {
        return new NumericDataObject(NumericSignature::UInt64, $value);
    }
    
    /**
     * Create double data object
     *
     * @param float|null $value
     * @return NumericDataObject
     */
    public static function d(?float $value = null): NumericDataObject
    {
        return new NumericDataObject(NumericSignature::Double, $value);
    }
    
    /**
     * Create variant data object
     *
     * @param BusctlDataObject $value
     * @return VariantDataObject
     */
    public static function v(BusctlDataObject $value): VariantDataObject
    {
        return new VariantDataObject($value);
    }
    
    /**
     * Create struct data object
     *
     * @param BusctlDataObject|BusctlDataObject[] $value
     * @return StructDataObject
     * @throws TypeError
     */
    public static function r(BusctlDataObject|array $value): StructDataObject
    {
        return new StructDataObject($value);
    }
    
    /**
     * Create array data object
     *
     * @param BusctlDataObject[] $value
     * @return ArrayDataObject
     * @throws TypeError
     */
    public static function a(array $value): ArrayDataObject
    {
        return new ArrayDataObject($value);
    }
    
    /**
     * Create map data object
     *
     * @param BusctlDataObject[][] $value
     * @return MapDataObject
     * @throws TypeError
     */
    public static function e(array $value): MapDataObject
    {
        return new MapDataObject($value);
    }
}