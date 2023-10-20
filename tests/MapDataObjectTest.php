<?php

declare(strict_types=1);

namespace Srhmster\PhpDbus\Tests;

use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;
use Srhmster\PhpDbus\DataObjects\{BusctlDataObject, MapDataObject};
use Srhmster\PhpDbus\Tests\DataProviders\DataObjectDataProvider;
use TypeError;

/**
 * MapDataObject class tests
 */
final class MapDataObjectTest extends TestCase
{
    /**
     * Can be created from valid value
     *
     * @see DataObjectDataProvider::validMapData()
     *
     * @param BusctlDataObject[][] $value
     * @param array $expected
     * @return void
     */
    #[DataProviderExternal(DataObjectDataProvider::class, 'validMapData')]
    public function testCanBeCreatedFromValidValue(
        array $value,
        array $expected
    ): void
    {
        $object = BusctlDataObject::e($value);
        
        $this->assertInstanceOf(MapDataObject::class, $object);
        $this->assertEquals($expected['value'], $object->getValue());
        $this->assertEquals(
            $expected['signature'] . ' ' . $expected['value'],
            $object->getValue(true)
        );
    }
    
    /**
     * Cannot be created from invalid value
     *
     * @see DataObjectDataProvider::invalidMapData()
     *
     * @param mixed $value
     * @return void
     */
    #[DataProviderExternal(DataObjectDataProvider::class, 'invalidMapData')]
    public function testCannotBeCreatedFromInvalidValue(mixed $value): void
    {
        $this->expectException(TypeError::class);
        
        BusctlDataObject::e($value);
    }
}