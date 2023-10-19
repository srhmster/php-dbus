<?php

namespace Srhmster\PhpDbus\Tests;

use BadMethodCallException;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Srhmster\PhpDbus\Commands\BusctlCommand;
use stdClass;

/**
 * BusctlCommand class tests
 */
class BusctlCommandTest extends TestCase
{
    /**
     * Valid data provider
     *
     * @return array
     */
    public function validDataProvider()
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
     * Invalid method data provide
     *
     * @return array
     */
    public function invalidMethodDataProvider()
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
     * Invalid useSudo flag data provider
     *
     * @return array
     */
    public function invalidUseSudoDataProvider()
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
     * Invalid options data provider
     *
     * @return array
     */
    public function invalidOptionsDataProvider()
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
            [['option', []]],
            [['option', new stdClass()]]
        ];
    }

    /**
     * Invalid attributes data provider
     *
     * @return array
     */
    public function invalidAttributesDataProvider()
    {
        return [
            [123],
            ['attribute'],
            [true],
            [new stdClass()],
        ];
    }

    /**
     * Can be converted to string from valid data
     *
     * @dataProvider validDataProvider
     *
     * @param string $method
     * @param bool $useSudo
     * @param array $options
     * @param array|null $attributes
     * @param string $expected
     * @return void
     */
    public function testCanBeConvertedToStringFromValidData(
        $method,
        $useSudo,
        $options,
        $attributes,
        $expected
    ) {
        $command = new BusctlCommand();
        $command
            ->setMethod($method)
            ->setUseSudo($useSudo)
            ->addOptions($options);

        $this->assertEquals($expected, $command->toString($attributes));
    }

    /**
     * Cannot be added invalid method
     *
     * @dataProvider invalidMethodDataProvider
     *
     * @param mixed $value
     * @return void
     */
    public function testCannotBeAddedInvalidMethod($value)
    {
        $this->expectException(InvalidArgumentException::class);

        $command = new BusctlCommand();
        $command->setMethod($value);
    }

    /**
     * Cannot be added invalid useSudo flag
     *
     * @dataProvider invalidUseSudoDataProvider
     *
     * @param mixed $value
     * @return void
     */
    public function testCannotBeAddedInvalidUseSudoFlag($value)
    {
        $this->expectException(InvalidArgumentException::class);

        $command = new BusctlCommand();
        $command->setUseSudo($value);
    }

    /**
     * Cannot be added invalid options
     *
     * @dataProvider invalidOptionsDataProvider
     *
     * @param mixed $value
     * @return void
     */
    public function testCannotBeAddedInvalidOptions($value)
    {
        $this->expectException(InvalidArgumentException::class);

        $command = new BusctlCommand();
        $command->addOptions($value);
    }

    /**
     * Cannot be executed with invalid attributes
     *
     * @dataProvider invalidAttributesDataProvider
     *
     * @param mixed $value
     * @return void
     */
    public function testCannotBeExecutedWithInvalidAttributes($value)
    {
        $this->expectException(InvalidArgumentException::class);

        $command = new BusctlCommand();
        $command->execute($value);
    }

    /**
     * Cannot be executed with an unspecified method
     *
     * @return void
     */
    public function testCannotBeExecutedWithUnspecifiedMethod()
    {
        $this->expectException(BadMethodCallException::class);

        $command = new BusctlCommand();
        $command->execute();
    }
}