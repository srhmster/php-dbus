<?php

declare(strict_types=1);

namespace Srhmster\PhpDbus\Tests;

use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;
use Srhmster\PhpDbus\DataObjects\{ArrayDataObject, BusctlDataObject};
use Srhmster\PhpDbus\Tests\DataProviders\DataObjectDataProvider;
use TypeError;

/**
 * ArrayDataObject class tests
 */
final class ArrayDataObjectTest extends TestCase
{
    /**
     * Can be created from valid value
     *
     * @see DataObjectDataProvider::validArrayData()
     *
     * @param BusctlDataObject[] $value
     * @param array $expected
     * @return void
     */
    #[DataProviderExternal(DataObjectDataProvider::class, 'validArrayData')]
    public function testCanBeCreatedFromValidValue(
        array $value,
        array $expected
    ): void
    {
        $object = BusctlDataObject::a($value);

        $this->assertInstanceOf(ArrayDataObject::class, $object);
        $this->assertEquals($expected['value'], $object->getValue());
        $this->assertEquals(
            $expected['signature'] . ' ' . $expected['value'],
            $object->getValue(true)
        );
    }

    /**
     * Cannot be created from invalid value
     *
     * @see DataObjectDataProvider::invalidArrayData()
     *
     * @param mixed $value
     * @return void
     */
    #[DataProviderExternal(DataObjectDataProvider::class, 'invalidArrayData')]
    public function testCannotBeCreatedFromInvalidValue(mixed $value): void
    {
        $this->expectException(TypeError::class);

        BusctlDataObject::a($value);
    }
}