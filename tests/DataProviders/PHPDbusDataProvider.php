<?php

declare(strict_types = 1);

namespace Srhmster\PhpDbus\Tests\DataProviders;

use stdClass;

/**
 * Data provider for PHP dbus tests
 */
final class PHPDbusDataProvider
{
    /**
     * Invalid service data
     *
     * @return array
     */
    public static function invalidServiceData(): array
    {
        return [
            [123],
            [null],
            [true],
            [[]],
            [new stdClass()]
        ];
    }
    
    /**
     * Invalid data for executed command methods
     *
     * @return array[]
     */
    public static function invalidExecutedMethodsData(): array
    {
        return [
            [null, 'string', 'string'],
            [123, 'string', 'string'],
            [true, 'string', 'string'],
            [[], 'string', 'string'],
            [new stdClass(), 'string', 'string'],
            ['string', null, 'string'],
            ['string', 123, 'string'],
            ['string', true, 'string'],
            ['string', [], 'string'],
            ['string', new stdClass(), 'string'],
            ['string', 'string', null],
            ['string', 'string', 123],
            ['string', 'string', true],
            ['string', 'string', []],
            ['string', 'string', new stdClass()],
        ];
    }
}