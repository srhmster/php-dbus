<?php

declare(strict_types=1);

namespace Srhmster\PhpDbus\Tests;

use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;
use Srhmster\PhpDbus\DataObjects\{BusctlDataObject, VariantDataObject};
use Srhmster\PhpDbus\Tests\DataProviders\DataObjectDataProvider;
use TypeError;

/**
 * VariantDataObject class tests
 */
final class VariantDataObjectTest extends TestCase
{
    /**
     * Can be created from valid value
     *
     * @see DataObjectDataProvider::validVariantData()
     *
     * @param BusctlDataObject $value
     * @param array $expected
     * @return void
     */
    #[DataProviderExternal(DataObjectDataProvider::class, 'validVariantData')]
    public function testCanBeCreatedFromValidValue(
        BusctlDataObject $value,
        array $expected
    ): void
    {
        $object = BusctlDataObject::v($value);
        
        $this->assertInstanceOf(VariantDataObject::class, $object);
        $this->assertEquals($expected['value'], $object->getValue());
        $this->assertEquals(
            VariantDataObject::SIGNATURE . ' ' . $expected['value'],
            $object->getValue(true)
        );
    }
    
    /**
     * Cannot be created from invalid value
     *
     * @see DataObjectDataProvider::invalidVariantData()
     *
     * @param mixed $value
     * @return void
     */
    #[DataProviderExternal(DataObjectDataProvider::class, 'invalidVariantData')]
    public function testCannotBeCreatedFromInvalidValue(mixed $value): void
    {
        $this->expectException(TypeError::class);
        
        BusctlDataObject::v($value);
    }
}