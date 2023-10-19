<?php

declare(strict_types=1);

namespace Srhmster\PhpDbus\Tests;

use PHPUnit\Framework\TestCase;
use Srhmster\PhpDbus\DataObjects\{BusctlDataObject, MapDataObject};
use TypeError;

/**
 * MapDataObject class tests
 */
final class MapDataObjectTest extends TestCase
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
    public function invalidDataProvider(): array
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
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     * @return void
     */
    public function testCannotBeCreatedFromInvalidValue($value): void
    {
        $this->expectException(TypeError::class);
        
        BusctlDataObject::e($value);
    }
}