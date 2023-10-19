<?php

declare(strict_types=1);

namespace Srhmster\PhpDbus\Tests;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Srhmster\PhpDbus\Commands\Command;
use Srhmster\PhpDbus\PHPDbus;
use stdClass;
use TypeError;

/**
 * PHPDbus class tests
 */
final class PHPDbusTest extends TestCase
{
    /**
     * Invalid service data provider
     *
     * @return array
     */
    public function invalidServiceDataProvider(): array
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
     * Invalid data provider for executed command methods
     *
     * @return array[]
     */
    public function invalidDataProviderForExecutedMethods(): array
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

    /**
     * Can be created from valid service
     *
     * @return void
     */
    public function testCanBeCreatedFromValidService(): void
    {
        $object = new PHPDbus('test.dbus.service');

        $this->assertInstanceOf(PHPDbus::class, $object);
    }

    /**
     * Cannot be created from invalid service
     *
     * @dataProvider invalidServiceDataProvider
     *
     * @param mixed $service
     * @return void
     */
    public function testCannotBeCreatedFromInvalidService($service): void
    {
        $this->expectException(TypeError::class);

        new PHPDbus($service);
    }

    /**
     * Cannot be executed call method from invalid data
     *
     * @dataProvider invalidDataProviderForExecutedMethods
     *
     * @param mixed $objectPath
     * @param mixed $interface
     * @param mixed $method
     * @return void
     */
    public function testCannotBeExecutedCallMethodFromInvalidData(
        $objectPath,
        $interface,
        $method
    ): void
    {
        $this->expectException(TypeError::class);

        $dbus = new PHPDbus('test.dbus.service', null, $this->getMockCommand());
        $dbus->call($objectPath, $interface, $method);
    }

    /**
     * Cannot be executed emit method from invalid data
     *
     * @dataProvider invalidDataProviderForExecutedMethods
     *
     * @param mixed $objectPath
     * @param mixed $interface
     * @param mixed $signal
     * @return void
     */
    public function testCannotBeExecutedEmitMethodFromInvalidData(
        $objectPath,
        $interface,
        $signal
    ): void
    {
        $this->expectException(TypeError::class);

        $dbus = new PHPDbus('test.dbus.service', null, $this->getMockCommand());
        $dbus->emit($objectPath, $interface, $signal);
    }

    /**
     * Cannot be executed get-property method from invalid data
     *
     * @dataProvider invalidDataProviderForExecutedMethods
     *
     * @param mixed $objectPath
     * @param mixed $interface
     * @param mixed $propertyName
     * @return void
     */
    public function testCannotBeExecutedGetPropertyMethodFromInvalidData(
        $objectPath,
        $interface,
        $propertyName
    ): void
    {
        $this->expectException(TypeError::class);

        $dbus = new PHPDbus('test.dbus.service', null, $this->getMockCommand());
        $dbus->getProperty($objectPath, $interface, $propertyName);
    }

    /**
     * Cannot be executed set-property method from invalid data
     *
     * @dataProvider invalidDataProviderForExecutedMethods
     *
     * @param mixed $objectPath
     * @param mixed $interface
     * @param mixed $propertyName
     * @return void
     */
    public function testCannotBeExecutedSetPropertyMethodFromInvalidData(
        $objectPath,
        $interface,
        $propertyName
    ): void
    {
        $this->expectException(TypeError::class);

        $dbus = new PHPDbus('test.dbus.service', null, $this->getMockCommand());
        $dbus->setProperty($objectPath, $interface, $propertyName);
    }

    /**
     * Get test command object
     *
     * @return Command|MockObject
     */
    private function getMockCommand()
    {
        $command = $this->createMock(Command::class);
        $command
            ->expects($this->any())
            ->method('execute')
            ->will($this->returnValue('s "hello world"'));

        return $command;
    }
}