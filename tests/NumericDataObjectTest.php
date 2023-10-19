<?php

declare(strict_types=1);

namespace Srhmster\PhpDbus\Tests;

use PHPUnit\Framework\TestCase;
use Srhmster\PhpDbus\DataObjects\{BusctlDataObject, NumericDataObject};
use TypeError;

/**
 * NumericDataObject class tests
 */
final class NumericDataObjectTest extends TestCase
{
    /**
     * Valid integer data provider
     *
     * @return array
     */
    public function validIntegerDataProvider(): array
    {
        return [
            [123, ['value' => 123]],
            [null, ['value' => null]],
        ];
    }

    /**
     * Valid double data provider
     *
     * @return array
     */
    public function validDoubleDataProvider(): array
    {
        return [
            [12.3, ['value' => 12.3]],
            [null, ['value' => null]],
        ];
    }
    
    /**
     * Invalid data provider
     *
     * @return array
     */
    public function invalidDataProvider(): array
    {
        return [
            ['string'],
            [true],
            [[]],
            [BusctlDataObject::y(123)],
        ];
    }

    /**
     * Invalid integer data provider
     *
     * @return array
     */
    public function invalidIntegerDataProvider(): array
    {
        return [[12.3]];
    }
    
    /**
     * Can be created from valid integer value
     *
     * @dataProvider validIntegerDataProvider
     *
     * @param int|null $value
     * @param array $expected
     * @return void
     */
    public function testCanBeCreatedFromValidIntValue(
        ?int $value,
        array $expected
    ): void
    {
        foreach ($this->getIntSignatures() as $signature) {
            /**
             * @see BusctlDataObject::y()
             * @see BusctlDataObject::n()
             * @see BusctlDataObject::q()
             * @see BusctlDataObject::i()
             * @see BusctlDataObject::u()
             * @see BusctlDataObject::x()
             * @see BusctlDataObject::t()
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
     * Can be created from valid double value
     *
     * @dataProvider validDoubleDataProvider
     *
     * @param float|null $value
     * @param array $expected
     * @return void
     */
    public function testCanBeCreatedFromValidDoubleValue(
        ?float $value,
        array $expected
    ): void
    {
        $object = BusctlDataObject::d($value);

        $this->assertInstanceOf(NumericDataObject::class, $object);
        $this->assertEquals($expected['value'], $object->getValue());
        $this->assertEquals(
            NumericDataObject::DOUBLE_SIGNATURE . ' ' . $expected['value'],
            $object->getValue(true)
        );
    }

    /**
     * Cannot be created byte from invalid value
     *
     * @dataProvider invalidDataProvider
     * @dataProvider invalidIntegerDataProvider
     *
     * @param mixed $value
     * @return void
     */
    public function testCannotBeCreatedByteFromInvalidValue($value): void
    {
        $this->expectException(TypeError::class);
        
        BusctlDataObject::y($value);
    }
    
    /**
     * Cannot be created int16 from invalid value
     *
     * @dataProvider invalidDataProvider
     * @dataProvider invalidIntegerDataProvider
     *
     * @param mixed $value
     * @return void
     */
    public function testCannotBeCreatedInt16FromInvalidValue($value): void
    {
        $this->expectException(TypeError::class);
    
        BusctlDataObject::n($value);
    }
    
    /**
     * Cannot be created uint16 from invalid value
     *
     * @dataProvider invalidDataProvider
     * @dataProvider invalidIntegerDataProvider
     *
     * @param mixed $value
     * @return void
     */
    public function testCannotBeCreatedUint16FromInvalidValue($value): void
    {
        $this->expectException(TypeError::class);
    
        BusctlDataObject::q($value);
    }
    
    /**
     * Cannot be created int32 from invalid value
     *
     * @dataProvider invalidDataProvider
     * @dataProvider invalidIntegerDataProvider
     *
     * @param mixed $value
     * @return void
     */
    public function testCannotBeCreatedInt32FromInvalidValue($value): void
    {
        $this->expectException(TypeError::class);
    
        BusctlDataObject::i($value);
    }
    
    /**
     * Cannot be created uint32 from invalid value
     *
     * @dataProvider invalidDataProvider
     * @dataProvider invalidIntegerDataProvider
     *
     * @param mixed $value
     * @return void
     */
    public function testCannotBeCreatedUint32FromInvalidValue($value): void
    {
        $this->expectException(TypeError::class);
    
        BusctlDataObject::u($value);
    }
    
    /**
     * Cannot be created int64 from invalid value
     *
     * @dataProvider invalidDataProvider
     * @dataProvider invalidIntegerDataProvider
     *
     * @param mixed $value
     * @return void
     */
    public function testCannotBeCreatedInt64FromInvalidValue($value): void
    {
        $this->expectException(TypeError::class);
    
        BusctlDataObject::x($value);
    }
    
    /**
     * Cannot be created uint64 from invalid value
     *
     * @dataProvider invalidDataProvider
     * @dataProvider invalidIntegerDataProvider
     *
     * @param mixed $value
     * @return void
     */
    public function testCannotBeCreatedUint64FromInvalidValue($value): void
    {
        $this->expectException(TypeError::class);
    
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
    public function testCannotBeCreatedDoubleFromInvalidValue($value): void
    {
        $this->expectException(TypeError::class);
    
        BusctlDataObject::d($value);
    }

    /**
     * Get integer signatures
     *
     * @return array
     */
    private function getIntSignatures(): array
    {
        return [
            NumericDataObject::BYTE_SIGNATURE,
            NumericDataObject::INT16_SIGNATURE,
            NumericDataObject::UINT16_SIGNATURE,
            NumericDataObject::INT32_SIGNATURE,
            NumericDataObject::UINT32_SIGNATURE,
            NumericDataObject::INT64_SIGNATURE,
            NumericDataObject::UINT64_SIGNATURE,
        ];
    }

}