<?php

declare(strict_types = 1);

namespace Srhmster\PhpDbus\Tests\DataProviders;

use stdClass;

/**
 * Data provider for command tests
 */
final class CommandDataProvider
{
    /**
     * Valid data
     *
     * @return array
     */
    public static function validData(): array
    {
        return [
            ['call', false, [], [], 'busctl call'],
            ['call', true, [], [], 'sudo busctl call'],
            ['call', false, ['help'], [], 'busctl --help call'],
            ['call', false, [['timeout', 5]], [], 'busctl --timeout=5 call'],
            [
                'call',
                false,
                [],
                [
                    'test.dbus.service',
                    '/test/object/path',
                    'test.dbus.interface',
                    'method',
                ],
                'busctl call test.dbus.service /test/object/path test.dbus.interface method'
            ],
            [
                'call',
                true,
                [['timeout', 5], 'verbose'],
                [
                    'test.dbus.service',
                    '/test/object/path',
                    'test.dbus.interface',
                    'method',
                ],
                'sudo busctl --timeout=5 --verbose call test.dbus.service '
                . '/test/object/path test.dbus.interface method'
            ],
        ];
    }
    
    /**
     * Invalid method data
     *
     * @return array
     */
    public static function invalidMethodData(): array
    {
        return [
            [123],
            [true],
            [null],
            [[]],
            [new stdClass()]
        ];
    }
    
    /**
     * Invalid use sudo data
     *
     * @return array
     */
    public static function invalidUseSudoData(): array
    {
        return [
            [123],
            ['true'],
            [null],
            [[]],
            [new stdClass()]
        ];
    }
    
    /**
     * Invalid options data
     *
     * @return array
     */
    public static function invalidOptionsData(): array
    {
        return [
            [123],
            [true],
            [null],
            [new stdClass()],
            [[123]],
            [[true]],
            [[null]],
            [[[]]],
            [[new stdClass()]],
            [[['option', []]]],
            [[['option', new stdClass()]]]
        ];
    }
    
    /**
     * Invalid attributes data
     *
     * @return array
     */
    public static function invalidAttributesData(): array
    {
        return [
            [123],
            ['attribute'],
            [true],
            [new stdClass()],
        ];
    }
}