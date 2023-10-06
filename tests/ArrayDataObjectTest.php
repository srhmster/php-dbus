<?php

namespace Srhmster\PhpDbus\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Srhmster\PhpDbus\DataObjects\ArrayDataObject;
use Srhmster\PhpDbus\DataObjects\BusctlDataObject;

/**
 * ArrayDataObject class tests
 */
class ArrayDataObjectTest extends TestCase
{
    /**
     * Valid data provider
     *
     * @return array
     */
    public function validDataProvider()
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
    public function invalidDataProvider()
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
    public function testCanBeCreatedFromValidValue($value, $expected)
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
     * @param BusctlDataObject[] $value
     * @return void
     */
    public function testCannotBeCreatedFromInvalidValue($value)
    {
        $this->expectException(InvalidArgumentException::class);

        BusctlDataObject::a($value);
    }
}