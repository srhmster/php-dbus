<?php

declare(strict_types=1);

namespace Srhmster\PhpDbus\Tests;

use PHPUnit\Framework\TestCase;
use Srhmster\PhpDbus\DataObjects\{ArrayDataObject, BusctlDataObject};
use TypeError;

/**
 * ArrayDataObject class tests
 */
final class ArrayDataObjectTest extends TestCase
{
    /**
     * Valid data provider
     *
     * @return array
     */
    public function validDataProvider(): array
    {
        return [
            [
                [BusctlDataObject::s('hello'), BusctlDataObject::s('world')],
                ['signature' => 'as', 'value' => '2 "hello" "world"'],
            ],
            [
                [
                    BusctlDataObject::a([BusctlDataObject::s('hello')]),
                    BusctlDataObject::a([BusctlDataObject::s('world')]),
                ],
                ['signature' => 'aas', 'value' => '2 1 "hello" 1 "world"'],
            ],
            [
                [BusctlDataObject::s()],
                ['signature' => 'as', 'value' => '0'],
            ]
        ];
    }
    
    /**
     * Invalid data objects data provider
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
            [BusctlDataObject::s('string')],
            [[]],
            [[BusctlDataObject::s('string'), BusctlDataObject::y(123)]],
            [[BusctlDataObject::s('hello'), 'world']],
        ];
    }
    
    /**
     * Can be created from valid value
     *
     * @dataProvider validDataProvider
     *
     * @param BusctlDataObject[] $value
     * @param array $expected
     * @return void
     */
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
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     * @return void
     */
    public function testCannotBeCreatedFromInvalidValue($value): void
    {
        $this->expectException(TypeError::class);

        BusctlDataObject::a($value);
    }
}