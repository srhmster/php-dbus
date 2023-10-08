<?php

namespace Srhmster\PhpDbus\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Srhmster\PhpDbus\Commands\BusctlCommand;
use Srhmster\PhpDbus\Commands\Command;
use Srhmster\PhpDbus\PHPDbus;
use stdClass;

/**
 * PHPDbus class tests
 */
class PHPDbusTest extends TestCase
{
    /**
     * Invalid service data provider
     *
     * @return array
     */
    public function invalidServiceDataProvider()
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
    public function invalidDataProviderForExecutedMethods()
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
    public function testCanBeCreatedFromValidService()
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
    public function testCannotBeCreatedFromInvalidService($service)
    {
        $this->expectException(InvalidArgumentException::class);

        $object = new PHPDbus($service);
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
    ) {
        $this->expectException(InvalidArgumentException::class);

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
    ) {
        $this->expectException(InvalidArgumentException::class);

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
    ) {
        $this->expectException(InvalidArgumentException::class);

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
    ) {
        $this->expectException(InvalidArgumentException::class);

        $dbus = new PHPDbus('test.dbus.service', null, $this->getMockCommand());
        $dbus->setProperty($objectPath, $interface, $propertyName);
    }

    /**
     * Get test command object
     *
     * @return Command|PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockCommand()
    {
        $command = $this->getMockBuilder(Command::class)->getMock();
        $command
            ->expects($this->any())
            ->method('execute')
            ->will($this->returnValue('s "hello world"'));

        return $command;
    }
}