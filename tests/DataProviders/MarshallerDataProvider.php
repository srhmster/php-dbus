<?php

declare(strict_types = 1);

namespace Srhmster\PhpDbus\Tests\DataProviders;

use Srhmster\PhpDbus\DataObjects\BusctlDataObject;
use stdClass;

/**
 * Data provider for marshaller tests
 */
final class MarshallerDataProvider
{
    /**
     * Valid data for marshalling
     *
     * @return array
     */
    public static function validMarshallingData(): array
    {
        return [
            [null, null],
            [BusctlDataObject::s(), 's '],
            [BusctlDataObject::s('hello world'), 's "hello world"'],
            [
                BusctlDataObject::r([
                    BusctlDataObject::s('string'),
                    BusctlDataObject::y(123)
                ]),
                '(sy) "string" 123'
            ],
            [
                BusctlDataObject::a([
                    BusctlDataObject::y(1),
                    BusctlDataObject::y(2),
                    BusctlDataObject::y(3),
                ]),
                'ay 3 1 2 3'
            ],
            [
                BusctlDataObject::v(BusctlDataObject::s('variant')),
                'v s "variant"'
            ],
            [
                BusctlDataObject::e([
                    [
                        'key' => BusctlDataObject::s('key'),
                        'value' => BusctlDataObject::v(BusctlDataObject::y(123))
                    ]
                ]),
                'a{sv} 1 "key" y 123'
            ],
            [
                BusctlDataObject::e([
                    [
                        'key' => BusctlDataObject::s('key'),
                        'value' => BusctlDataObject::e([
                            [
                                'key' => BusctlDataObject::s('item'),
                                'value' => BusctlDataObject::v(
                                    BusctlDataObject::u(123)
                                )
                            ]
                        ])
                    ]
                ]),
                'a{sa{sv}} 1 "key" 1 "item" u 123'
            ],
            [
                [
                    BusctlDataObject::s('hello'),
                    BusctlDataObject::s('world'),
                ],
                'ss "hello" "world"'
            ]
        ];
    }
    
    /**
     * Valid data for unmarshalling
     *
     * @return array
     */
    public static function validUnmarshallingData(): array
    {
        return [
            ['s', null],
            ['as 0', []],
            ['s "hello world"', 'hello world'],
            ['su "hello world" 123', ['hello world', 123]],
            ['ay 3 1 2 3', [1, 2, 3]],
            [
                'a{sa{sv}} 1 "key" 1 "item" y 123',
                ['key' => ['item' => 123]]
            ],
        ];
    }
    
    /**
     * Invalid data for marshalling
     *
     * @return array
     */
    public static function invalidMarshallingData(): array
    {
        return [
            [123],
            ['string'],
            [true],
            [[]],
            [[123]],
            [new stdClass()]
        ];
    }
    
    /**
     * Invalid data for unmarshalling
     *
     * @return array
     */
    public static function invalidUnmarshallingData(): array
    {
        return [
            [null],
            [123],
            [true],
            [[]],
            [new stdClass()],
            ['sp "hello world" 123'],
        ];
    }
}