<?php

namespace Srhmster\PhpDbus\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Srhmster\PhpDbus\DataObjects\BusctlDataObject;
use Srhmster\PhpDbus\DataObjects\NumericDataObject;

/**
 * NumericDataObject class tests
 */
class NumericDataObjectTest extends TestCase
{
    /**
     * Valid data provider
     *
     * @return array
     */
    public function validDataProvider()
    {
        return [
            [123, ['value' => 123]],
            [12.3, ['value' => 12.3]],
            [null, ['value' => null]],
        ];
    }
    
    /**
     * Invalid data provider
     *
     * @return array
     */
    public function invalidDataProvider()
    {
        return [
            ['string'],
            [true],
            [[]],
            [BusctlDataObject::y(123)],
        ];
    }
    
    /**
     * Can be created from valid value
     *
     * @dataProvider validDataProvider
     *
     * @param int|float|null $value
     * @param array $expected
     * @return void
     */
    public function testCanBeCreatedFromValidValue($value, $expected)
    {
        $signatures = [
            NumericDataObject::BYTE_SIGNATURE,
            NumericDataObject::INT16_SIGNATURE,
            NumericDataObject::UINT16_SIGNATURE,
            NumericDataObject::INT32_SIGNATURE,
            NumericDataObject::UINT32_SIGNATURE,
            NumericDataObject::INT64_SIGNATURE,
            NumericDataObject::UINT64_SIGNATURE,
            NumericDataObject::DOUBLE_SIGNATURE,
        ];
        
        foreach ($signatures as $signature) {
            /**
             * @see BusctlDataObject::y()
             * @see BusctlDataObject::n()
             * @see BusctlDataObject::q()
             * @see BusctlDataObject::i()
             * @see BusctlDataObject::u()
             * @see BusctlDataObject::x()
             * @see BusctlDataObject::t()
             * @see BusctlDataObject::d()
             *
             * @var NumericDataObject $object
             */
            $object = BusctlDataObject::$signature($value);
            
            $this->assertInstanceOf(NumericDataObject::class, $object);
            $this->assertEquals($expected['value'], $object->getValue());
            $this->assertEquals(
                $signature . ' ' . $expected['value'],
                $object->getValue(true)
            );
        }
    }
    
    /**
     * Cannot be created byte from invalid value
     *
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     * @return void
     */
    public function testCannotBeCreatedByteFromInvalidValue($value)
    {
        $this->expectException(InvalidArgumentException::class);
        
        BusctlDataObject::y($value);
    }
    
    /**
     * Cannot be created int16 from invalid value
     *
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     * @return void
     */
    public function testCannotBeCreatedInt16FromInvalidValue($value)
    {
        $this->expectException(InvalidArgumentException::class);
    
        BusctlDataObject::n($value);
    }
    
    /**
     * Cannot be created uint16 from invalid value
     *
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     * @return void
     */
    public function testCannotBeCreatedUint16FromInvalidValue($value)
    {
        $this->expectException(InvalidArgumentException::class);
    
        BusctlDataObject::q($value);
    }
    
    /**
     * Cannot be created int32 from invalid value
     *
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     * @return void
     */
    public function testCannotBeCreatedInt32FromInvalidValue($value)
    {
        $this->expectException(InvalidArgumentException::class);
    
        BusctlDataObject::i($value);
    }
    
    /**
     * Cannot be created uint32 from invalid value
     *
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     * @return void
     */
    public function testCannotBeCreatedUint32FromInvalidValue($value)
    {
        $this->expectException(InvalidArgumentException::class);
    
        BusctlDataObject::u($value);
    }
    
    /**
     * Cannot be created int64 from invalid value
     *
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     * @return void
     */
    public function testCannotBeCreatedInt64FromInvalidValue($value)
    {
        $this->expectException(InvalidArgumentException::class);
    
        BusctlDataObject::x($value);
    }
    
    /**
     * Cannot be created uint64 from invalid value
     *
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     * @return void
     */
    public function testCannotBeCreatedUint64FromInvalidValue($value)
    {
        $this->expectException(InvalidArgumentException::class);
    
        BusctlDataObject::t($value);
    }
    
    /**
     * Cannot be created double from invalid value
     *
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     * @return void
     */
    public function testCannotBeCreatedDoubleFromInvalidValue($value)
    {
        $this->expectException(InvalidArgumentException::class);
    
        BusctlDataObject::d($value);
    }
}