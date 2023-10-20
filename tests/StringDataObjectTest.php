<?php

declare(strict_types=1);

namespace Srhmster\PhpDbus\Tests;

use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;
use Srhmster\PhpDbus\DataObjects\{BusctlDataObject, StringDataObject};
use Srhmster\PhpDbus\Tests\DataProviders\DataObjectDataProvider;
use TypeError;

/**
 * StringDataObject class tests
 */
final class StringDataObjectTest extends TestCase
{
    /**
     * Can be created from valid value
     *
     * @see DataObjectDataProvider::validStringData()
     *
     * @param string|null $value
     * @param array $expected
     * @return void
     */
    #[DataProviderExternal(DataObjectDataProvider::class, 'validStringData')]
    public function testCanBeCreatedFromValidValue(
        ?string $value,
        array $expected
    ): void
    {
        $object = BusctlDataObject::s($value);
        
        $this->assertInstanceOf(StringDataObject::class, $object);
        $this->assertEquals($expected['value'], $object->getValue());
        $this->assertEquals(
            StringDataObject::SIGNATURE . ' ' . $expected['value'],
            $object->getValue(true)
        );
    }
    
    /**
     * Cannot be created from invalid value
     *
     * @see DataObjectDataProvider::invalidStringData()
     *
     * @param mixed $value
     * @return void
     */
    #[DataProviderExternal(DataObjectDataProvider::class, 'invalidStringData')]
    public function testCannotBeCreatedFromInvalidValue(mixed $value): void
    {
        $this->expectException(TypeError::class);
        
        BusctlDataObject::s($value);
    }
}