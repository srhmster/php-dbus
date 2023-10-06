<?php

namespace Srhmster\PhpDbus\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Srhmster\PhpDbus\DataObjects\BusctlDataObject;
use Srhmster\PhpDbus\DataObjects\StructDataObject;

/**
 * StructDataObject class tests
 */
class StructDataObjectTest extends TestCase
{
    /**
     * Valid data provider
     *
     * @return array
     */
    public function validDataProvider()
    {
        return [
            [BusctlDataObject::s(), ['signature' => '(s)', 'value' => null]],
            [
                [BusctlDataObject::s('hello world'), BusctlDataObject::y(123)],
                ['signature' => '(sy)', 'value' => '"hello world" 123'],
            ],
            [
                [
                    BusctlDataObject::r([
                        BusctlDataObject::y(1),
                        BusctlDataObject::b(true),
                        BusctlDataObject::s('string'),
                    ])
                ],
                ['signature' => '((ybs))', 'value' => '1 true "string"']
            ],
            [
                [
                    BusctlDataObject::a([
                        BusctlDataObject::s('hello'),
                        BusctlDataObject::s('world'),
                    ]),
                    BusctlDataObject::y(123),
                ],
                ['signature' => '(asy)', 'value' => '2 "hello" "world" 123']
            ]
        ];
    }
    
    /**
     * Invalid data provider
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
            [[]],
            [null],
            [[BusctlDataObject::s('hello'), 'world']],
        ];
    }
    
    /**
     * Can be created from valid value
     *
     * @dataProvider validDataProvider
     *
     * @param BusctlDataObject|BusctlDataObject[] $value
     * @param array $expected
     * @return void
     */
    public function testCanBeCreatedFromValidValue($value, $expected)
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
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     * @return void
     */
    public function testCannotBeCreatedFromInvalidValue($value)
    {
        $this->expectException(InvalidArgumentException::class);
        
        BusctlDataObject::r($value);
    }
}