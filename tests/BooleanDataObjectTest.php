<?php

declare(strict_types=1);

namespace Srhmster\PhpDbus\Tests;

use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;
use Srhmster\PhpDbus\DataObjects\{BooleanDataObject, BusctlDataObject};
use Srhmster\PhpDbus\Tests\DataProviders\DataObjectDataProvider;
use TypeError;

/**
 * BooleanDataObject class tests
 */
final class BooleanDataObjectTest extends TestCase
{
    /**
     * Can be created from valid value
     *
     * @see DataObjectDataProvider::validBooleanData()
     *
     * @param bool|null $value
     * @param array $expected
     * @return void
     */
    #[DataProviderExternal(DataObjectDataProvider::class, 'validBooleanData')]
    public function testCanBeCreatedFromValidValue(
        ?bool $value,
        array $expected
    ): void
    {
        $object = BusctlDataObject::b($value);
        
        $this->assertInstanceOf(BooleanDataObject::class, $object);
        $this->assertEquals($expected['value'], $object->getValue());
        $this->assertEquals(
            BooleanDataObject::SIGNATURE . ' ' . $expected['value'],
            $object->getValue(true)
        );
    }
    
    /**
     * Cannot be created from invalid value
     *
     * @see DataObjectDataProvider::invalidBooleanData()
     *
     * @param mixed $value
     * @return void
     */
    #[DataProviderExternal(DataObjectDataProvider::class, 'invalidBooleanData')]
    public function testCannotBeCreatedFromInvalidValue(mixed $value): void
    {
        $this->expectException(TypeError::class);
        
        BusctlDataObject::b($value);
    }
}