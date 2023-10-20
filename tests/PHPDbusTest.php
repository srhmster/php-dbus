<?php

declare(strict_types=1);

namespace Srhmster\PhpDbus\Tests;

use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Srhmster\PhpDbus\Commands\Command;
use Srhmster\PhpDbus\PHPDbus;
use Srhmster\PhpDbus\Tests\DataProviders\PHPDbusDataProvider;
use TypeError;

/**
 * PHPDbus class tests
 */
final class PHPDbusTest extends TestCase
{
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
     * @see PHPDbusDataProvider::invalidServiceData()
     *
     * @param mixed $service
     * @return void
     */
    #[DataProviderExternal(PHPDbusDataProvider::class, 'invalidServiceData')]
    public function testCannotBeCreatedFromInvalidService(mixed $service): void
    {
        $this->expectException(TypeError::class);

        new PHPDbus($service);
    }

    /**
     * Cannot be executed call method from invalid data
     *
     * @see PHPDbusDataProvider::invalidExecutedMethodsData()
     *
     * @param mixed $objectPath
     * @param mixed $interface
     * @param mixed $method
     * @return void
     * @throws Exception
     */
    #[DataProviderExternal(PHPDbusDataProvider::class, 'invalidExecutedMethodsData')]
    public function testCannotBeExecutedCallMethodFromInvalidData(
        mixed $objectPath,
        mixed $interface,
        mixed $method
    ): void
    {
        $this->expectException(TypeError::class);

        $dbus = new PHPDbus('test.dbus.service', null, $this->getMockCommand());
        $dbus->call($objectPath, $interface, $method);
    }

    /**
     * Cannot be executed emit method from invalid data
     *
     * @see PHPDbusDataProvider::invalidExecutedMethodsData()
     *
     * @param mixed $objectPath
     * @param mixed $interface
     * @param mixed $signal
     * @return void
     * @throws Exception
     */
    #[DataProviderExternal(PHPDbusDataProvider::class, 'invalidExecutedMethodsData')]
    public function testCannotBeExecutedEmitMethodFromInvalidData(
        mixed $objectPath,
        mixed $interface,
        mixed $signal
    ): void
    {
        $this->expectException(TypeError::class);

        $dbus = new PHPDbus('test.dbus.service', null, $this->getMockCommand());
        $dbus->emit($objectPath, $interface, $signal);
    }

    /**
     * Cannot be executed get-property method from invalid data
     *
     * @see PHPDbusDataProvider::invalidExecutedMethodsData()
     *
     * @param mixed $objectPath
     * @param mixed $interface
     * @param mixed $propertyName
     * @return void
     * @throws Exception
     */
    #[DataProviderExternal(PHPDbusDataProvider::class, 'invalidExecutedMethodsData')]
    public function testCannotBeExecutedGetPropertyMethodFromInvalidData(
        mixed $objectPath,
        mixed $interface,
        mixed $propertyName
    ): void
    {
        $this->expectException(TypeError::class);

        $dbus = new PHPDbus('test.dbus.service', null, $this->getMockCommand());
        $dbus->getProperty($objectPath, $interface, $propertyName);
    }
    
    /**
     * Cannot be executed set-property method from invalid data
     *
     * @see PHPDbusDataProvider::invalidExecutedMethodsData()
     *
     * @param mixed $objectPath
     * @param mixed $interface
     * @param mixed $propertyName
     * @return void
     * @throws Exception
     */
    #[DataProviderExternal(PHPDbusDataProvider::class, 'invalidExecutedMethodsData')]
    public function testCannotBeExecutedSetPropertyMethodFromInvalidData(
        mixed $objectPath,
        mixed $interface,
        mixed $propertyName
    ): void
    {
        $this->expectException(TypeError::class);

        $dbus = new PHPDbus('test.dbus.service', null, $this->getMockCommand());
        $dbus->setProperty($objectPath, $interface, $propertyName);
    }
    
    /**
     * Get test command object
     *
     * @return MockObject|Command
     * @throws Exception
     */
    private function getMockCommand(): Command|MockObject
    {
        return $this->createConfiguredMock(
            Command::class,
            ['execute' => 's "hello world"']
        );
    }
}