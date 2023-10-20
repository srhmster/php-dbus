<?php

declare(strict_types = 1);

namespace Srhmster\PhpDbus\Tests\DataProviders;

use Srhmster\PhpDbus\DataObjects\BusctlDataObject;

/**
 * Data provider for data object tests
 */
final class DataObjectDataProvider
{
    /**
     * Valid data for array data object
     *
     * @return array
     */
    public static function validArrayData(): array
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
     * Invalid data for array data object
     *
     * @return array
     */
    public static function invalidArrayData(): array
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
     * Valid data for boolean data object
     *
     * @return array
     */
    public static function validBooleanData(): array
    {
        return [
            [true, ['value' => 'true']],
            [false, ['value' => 'false']],
            [null, ['value' => null]],
        ];
    }
    
    /**
     * Invalid data for boolean data object
     *
     * @return array
     */
    public static function invalidBooleanData(): array
    {
        return [
            ['string'],
            [123],
            [12.3],
            [[]],
            [BusctlDataObject::b(true)]
        ];
    }
    
    /**
     * Valid data for map data object
     *
     * @return array
     */
    public static function validMapData(): array
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
     * Invalid data for map data object
     *
     * @return array
     */
    public static function invalidMapData(): array
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
     * Valid integer data for numeric data object
     *
     * @return array
     */
    public static function validIntegerDataForNumeric(): array
    {
        return [
            [123, ['value' => 123]],
            [null, ['value' => null]],
        ];
    }
    
    /**
     * Valid double data for numeric data object
     *
     * @return array
     */
    public static function validDoubleDataForNumeric(): array
    {
        return [
            [12.3, ['value' => 12.3]],
            [null, ['value' => null]],
        ];
    }
    
    /**
     * Invalid data for numeric data object
     *
     * @return array
     */
    public static function invalidNumericData(): array
    {
        return [
            ['string'],
            [true],
            [[]],
            [BusctlDataObject::y(123)],
        ];
    }
    
    /**
     * Invalid integer data for numeric data object
     *
     * @return array
     */
    public static function invalidIntegerDataForNumeric(): array
    {
        return [[12.3]];
    }
    
    /**
     * Valid data for object path data object
     *
     * @return array
     */
    public static function validObjectPathData(): array
    {
        return [
            ['/', ['value' => '/']],
            ['/path/to/object', ['value' => '/path/to/object']],
            [null, ['value' => null]],
        ];
    }
    
    /**
     * Invalid data for object path data object
     *
     * @return array
     */
    public static function invalidObjectPathData(): array
    {
        return [
            ['string'],
            ['/path/to/object/'],
            ['path/to/object'],
            ['/path/to/new.object'],
            [123],
            [12.3],
            [true],
            [[]],
            [BusctlDataObject::o('/object/path')]
        ];
    }
    
    /**
     * Valid data for string data object
     *
     * @return array
     */
    public static function validStringData(): array
    {
        return [
            ['string', ['value' => '"string"']],
            [null, ['value' => null]],
        ];
    }
    
    /**
     * Invalid data for string data object
     *
     * @return array
     */
    public static function invalidStringData(): array
    {
        return [
            [123],
            [12.3],
            [true],
            [[]],
            [BusctlDataObject::s('string')],
        ];
    }
    
    /**
     * Valid data for struct data object
     *
     * @return array
     */
    public static function validStructData(): array
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
     * Invalid data for struct data object
     *
     * @return array
     */
    public static function invalidStructData(): array
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
     * Valid data for variant data object
     *
     * @return array
     */
    public static function validVariantData(): array
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
     * Invalid data for variant data object
     *
     * @return array
     */
    public static function invalidVariantData(): array
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
}