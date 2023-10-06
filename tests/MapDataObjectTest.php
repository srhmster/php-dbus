<?php

namespace Srhmster\PhpDbus\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Srhmster\PhpDbus\DataObjects\BusctlDataObject;
use Srhmster\PhpDbus\DataObjects\MapDataObject;

/**
 * MapDataObject class tests
 */
class MapDataObjectTest extends TestCase
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
                [
                    [
                        'key' => BusctlDataObject::s(),
                        'value' => BusctlDataObject::y(),
                    ]
                ],
                [
                    'signature' => 'a{sy}',
                    'value' => '0'
                ]
            ],
            [
                [
                    [
                        'key' => BusctlDataObject::s('hello'),
                        'value' => BusctlDataObject::s('world'),
                    ]
                ],
                [
                    'signature' => 'a{ss}',
                    'value' => '1 "hello" "world"'
                ]
            ],
            [
                [
                    [
                        'key' => BusctlDataObject::s('hello'),
                        'value' => BusctlDataObject::a([
                            BusctlDataObject::s('old world'),
                            BusctlDataObject::s('new world'),
                        ])
                    ]
                ],
                [
                    'signature' => 'a{sas}',
                    'value' => '1 "hello" 2 "old world" "new world"'
                ]
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
            [null],
            [[]],
            [BusctlDataObject::s('string')],
            [
                ['hello', 'world'],
            ],
            [
                [
                    [
                        BusctlDataObject::s('hello'),
                        BusctlDataObject::s('world'),
                    ],
                ],
            ],
            [
                [
                    [
                        'key' => BusctlDataObject::r(BusctlDataObject::s('key')),
                        'value' => BusctlDataObject::y(123),
                    ]
                ]
            ],
            [
                [
                    [
                        'key' => null,
                        'value' => BusctlDataObject::s('string')
                    ]
                ]
            ],
            [
                [
                    [
                        'key' => BusctlDataObject::s('string'),
                        'value' => null,
                    ]
                ]
            ]
        ];
    }
    
    /**
     * Can be created from valid value
     *
     * @dataProvider validDataProvider
     *
     * @param BusctlDataObject[][] $value
     * @param array $expected
     * @return void
     */
    public function testCanBeCreatedFromValidValue($value, $expected)
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
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     * @return void
     */
    public function testCannotBeCreatedFromInvalidValue($value)
    {
        $this->expectException(InvalidArgumentException::class);
        
        BusctlDataObject::e($value);
    }
}