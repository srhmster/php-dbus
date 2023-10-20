<?php

declare(strict_types=1);

namespace Srhmster\PhpDbus\Tests;

use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;
use Srhmster\PhpDbus\DataObjects\{BusctlDataObject, StructDataObject};
use Srhmster\PhpDbus\Tests\DataProviders\DataObjectDataProvider;
use TypeError;

/**
 * StructDataObject class tests
 */
final class StructDataObjectTest extends TestCase
{
    /**
     * Can be created from valid value
     *
     * @see DataObjectDataProvider::validStructData()
     *
     * @param BusctlDataObject|BusctlDataObject[] $value
     * @param array $expected
     * @return void
     */
    #[DataProviderExternal(DataObjectDataProvider::class, 'validStructData')]
    public function testCanBeCreatedFromValidValue(
        BusctlDataObject|array $value,
        array $expected
    ): void
    {
        $object = BusctlDataObject::r($value);
        
        $this->assertInstanceOf(StructDataObject::class, $object);
        $this->assertEquals($expected['value'], $object->getValue());
        $this->assertEquals(
            $expected['signature'] . ' ' . $expected['value'],
            $object->getValue(true)
        );
    }
    
    /**
     * Cannot be created from invalid value
     *
     * @see DataObjectDataProvider::invalidStructData()
     *
     * @param mixed $value
     * @return void
     */
    #[DataProviderExternal(DataObjectDataProvider::class, 'invalidStructData')]
    public function testCannotBeCreatedFromInvalidValue(mixed $value): void
    {
        $this->expectException(TypeError::class);
        
        BusctlDataObject::r($value);
    }
}