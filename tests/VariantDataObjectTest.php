<?php

declare(strict_types=1);

namespace Srhmster\PhpDbus\Tests;

use PHPUnit\Framework\TestCase;
use Srhmster\PhpDbus\DataObjects\{BusctlDataObject, VariantDataObject};
use TypeError;

/**
 * VariantDataObject class tests
 */
final class VariantDataObjectTest extends TestCase
{
    /**
     * Valid data provider
     *
     * @return array
     */
    public function validDataProvider(): array
    {
        return [
            [BusctlDataObject::s(), ['value' => null]],
            [
                BusctlDataObject::s('hello world'),
                ['value' => 's "hello world"']
            ],
            [
                BusctlDataObject::a([
                    BusctlDataObject::s('hello'),
                    BusctlDataObject::s('world')
                ]),
                ['value' => 'as 2 "hello" "world"']
            ]
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
            [123],
            [12.3],
            [true],
            [null],
            [[]],
        ];
    }
    
    /**
     * Can be created from valid value
     *
     * @dataProvider validDataProvider
     *
     * @param BusctlDataObject $value
     * @param array $expected
     * @return void
     */
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
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     * @return void
     */
    public function testCannotBeCreatedFromInvalidValue($value): void
    {
        $this->expectException(TypeError::class);
        
        BusctlDataObject::v($value);
    }
}