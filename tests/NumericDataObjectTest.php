<?php

declare(strict_types=1);

namespace Srhmster\PhpDbus\Tests;

use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;
use Srhmster\PhpDbus\DataObjects\{BusctlDataObject, NumericDataObject, NumericSignature};
use Srhmster\PhpDbus\Tests\DataProviders\DataObjectDataProvider;
use TypeError;

/**
 * NumericDataObject class tests
 */
final class NumericDataObjectTest extends TestCase
{
    /**
     * Can be created from valid integer value
     *
     * @see DataObjectDataProvider::validIntegerDataForNumeric()
     *
     * @param int|null $value
     * @param array $expected
     * @return void
     */
    #[DataProviderExternal(DataObjectDataProvider::class, 'validIntegerDataForNumeric')]
    public function testCanBeCreatedFromValidIntValue(
        ?int $value,
        array $expected
    ): void
    {
        foreach (NumericSignature::onlyIntegerValues() as $signature) {
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
     * @see DataObjectDataProvider::validDoubleDataForNumeric()
     *
     * @param float|null $value
     * @param array $expected
     * @return void
     */
    #[DataProviderExternal(DataObjectDataProvider::class, 'validDoubleDataForNumeric')]
    public function testCanBeCreatedFromValidDoubleValue(
        ?float $value,
        array $expected
    ): void
    {
        $object = BusctlDataObject::d($value);

        $this->assertInstanceOf(NumericDataObject::class, $object);
        $this->assertEquals($expected['value'], $object->getValue());
        $this->assertEquals(
            NumericSignature::Double->value . ' ' . $expected['value'],
            $object->getValue(true)
        );
    }

    /**
     * Cannot be created byte from invalid value
     *
     * @see DataObjectDataProvider::invalidNumericData()
     * @see DataObjectDataProvider::invalidIntegerDataForNumeric()
     *
     * @param mixed $value
     * @return void
     */
    #[DataProviderExternal(DataObjectDataProvider::class, 'invalidNumericData')]
    #[DataProviderExternal(DataObjectDataProvider::class, 'invalidIntegerDataForNumeric')]
    public function testCannotBeCreatedByteFromInvalidValue(mixed $value): void
    {
        $this->expectException(TypeError::class);
        
        BusctlDataObject::y($value);
    }
    
    /**
     * Cannot be created int16 from invalid value
     *
     * @see DataObjectDataProvider::invalidNumericData()
     * @see DataObjectDataProvider::invalidIntegerDataForNumeric()
     *
     * @param mixed $value
     * @return void
     */
    #[DataProviderExternal(DataObjectDataProvider::class, 'invalidNumericData')]
    #[DataProviderExternal(DataObjectDataProvider::class, 'invalidIntegerDataForNumeric')]
    public function testCannotBeCreatedInt16FromInvalidValue(mixed $value): void
    {
        $this->expectException(TypeError::class);
    
        BusctlDataObject::n($value);
    }
    
    /**
     * Cannot be created uint16 from invalid value
     *
     * @see DataObjectDataProvider::invalidNumericData()
     * @see DataObjectDataProvider::invalidIntegerDataForNumeric()
     *
     * @param mixed $value
     * @return void
     */
    #[DataProviderExternal(DataObjectDataProvider::class, 'invalidNumericData')]
    #[DataProviderExternal(DataObjectDataProvider::class, 'invalidIntegerDataForNumeric')]
    public function testCannotBeCreatedUint16FromInvalidValue(mixed $value): void
    {
        $this->expectException(TypeError::class);
    
        BusctlDataObject::q($value);
    }
    
    /**
     * Cannot be created int32 from invalid value
     *
     * @see DataObjectDataProvider::invalidNumericData()
     * @see DataObjectDataProvider::invalidIntegerDataForNumeric()
     *
     * @param mixed $value
     * @return void
     */
    #[DataProviderExternal(DataObjectDataProvider::class, 'invalidNumericData')]
    #[DataProviderExternal(DataObjectDataProvider::class, 'invalidIntegerDataForNumeric')]
    public function testCannotBeCreatedInt32FromInvalidValue(mixed $value): void
    {
        $this->expectException(TypeError::class);
    
        BusctlDataObject::i($value);
    }
    
    /**
     * Cannot be created uint32 from invalid value
     *
     * @see DataObjectDataProvider::invalidNumericData()
     * @see DataObjectDataProvider::invalidIntegerDataForNumeric()
     *
     * @param mixed $value
     * @return void
     */
    #[DataProviderExternal(DataObjectDataProvider::class, 'invalidNumericData')]
    #[DataProviderExternal(DataObjectDataProvider::class, 'invalidIntegerDataForNumeric')]
    public function testCannotBeCreatedUint32FromInvalidValue(mixed $value): void
    {
        $this->expectException(TypeError::class);
    
        BusctlDataObject::u($value);
    }
    
    /**
     * Cannot be created int64 from invalid value
     *
     * @see DataObjectDataProvider::invalidNumericData()
     * @see DataObjectDataProvider::invalidIntegerDataForNumeric()
     *
     * @param mixed $value
     * @return void
     */
    #[DataProviderExternal(DataObjectDataProvider::class, 'invalidNumericData')]
    #[DataProviderExternal(DataObjectDataProvider::class, 'invalidIntegerDataForNumeric')]
    public function testCannotBeCreatedInt64FromInvalidValue(mixed $value): void
    {
        $this->expectException(TypeError::class);
    
        BusctlDataObject::x($value);
    }
    
    /**
     * Cannot be created uint64 from invalid value
     *
     * @see DataObjectDataProvider::invalidNumericData()
     * @see DataObjectDataProvider::invalidIntegerDataForNumeric()
     *
     * @param mixed $value
     * @return void
     */
    #[DataProviderExternal(DataObjectDataProvider::class, 'invalidNumericData')]
    #[DataProviderExternal(DataObjectDataProvider::class, 'invalidIntegerDataForNumeric')]
    public function testCannotBeCreatedUint64FromInvalidValue(mixed $value): void
    {
        $this->expectException(TypeError::class);
    
        BusctlDataObject::t($value);
    }
    
    /**
     * Cannot be created double from invalid value
     *
     * @see DataObjectDataProvider::invalidNumericData()
     *
     * @param mixed $value
     * @return void
     */
    #[DataProviderExternal(DataObjectDataProvider::class, 'invalidNumericData')]
    public function testCannotBeCreatedDoubleFromInvalidValue(mixed $value): void
    {
        $this->expectException(TypeError::class);
    
        BusctlDataObject::d($value);
    }
}