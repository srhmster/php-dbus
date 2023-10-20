<?php

declare(strict_types=1);

namespace Srhmster\PhpDbus\Tests;

use BadMethodCallException;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;
use Srhmster\PhpDbus\Commands\BusctlCommand;
use Srhmster\PhpDbus\Tests\DataProviders\CommandDataProvider;
use TypeError;

/**
 * BusctlCommand class tests
 */
final class BusctlCommandTest extends TestCase
{
    /**
     * Can be converted to string from valid data
     *
     * @see CommandDataProvider::validData()
     *
     * @param string $method
     * @param bool $useSudo
     * @param array $options
     * @param array|null $attributes
     * @param string $expected
     * @return void
     */
    #[DataProviderExternal(CommandDataProvider::class, 'validData')]
    public function testCanBeConvertedToStringFromValidData(
        string $method,
        bool $useSudo,
        array $options,
        ?array $attributes,
        string $expected
    ): void
    {
        $command = new BusctlCommand();
        $command
            ->setMethod($method)
            ->setUseSudo($useSudo)
            ->addOptions($options);

        $this->assertEquals($expected, $command->toString($attributes));
    }
    
    /**
     * Cannot be added invalid data
     *
     * @see CommandDataProvider::invalidMethodData()
     *
     * @param mixed $value
     * @return void
     */
    #[DataProviderExternal(CommandDataProvider::class, 'invalidMethodData')]
    public function testCannotBeAddedInvalidMethod(mixed $value): void
    {
        $this->expectException(TypeError::class);

        $command = new BusctlCommand();
        $command->setMethod($value);
    }
    
    /**
     * Cannot be added invalid use sudo flag
     *
     * @see CommandDataProvider::invalidUseSudoData()
     *
     * @param mixed $value
     * @return void
     */
    #[DataProviderExternal(CommandDataProvider::class, 'invalidUseSudoData')]
    public function testCannotBeAddedInvalidUseSudoFlag(mixed $value): void
    {
        $this->expectException(TypeError::class);

        $command = new BusctlCommand();
        $command->setUseSudo($value);
    }
    
    /**
     * Cannot be added invalid options
     *
     * @see CommandDataProvider::invalidOptionsData()
     *
     * @param mixed $value
     * @return void
     */
    #[DataProviderExternal(CommandDataProvider::class, 'invalidOptionsData')]
    public function testCannotBeAddedInvalidOptions(mixed $value): void
    {
        $this->expectException(TypeError::class);

        $command = new BusctlCommand();
        $command->addOptions($value);
    }
    
    /**
     * Cannot be executed with invalid attributes
     *
     * @see CommandDataProvider::invalidAttributesData()
     *
     * @param mixed $value
     * @return void
     */
    #[DataProviderExternal(CommandDataProvider::class, 'invalidAttributesData')]
    public function testCannotBeExecutedWithInvalidAttributes(mixed $value): void
    {
        $this->expectException(TypeError::class);

        $command = new BusctlCommand();
        $command->execute($value);
    }
    
    /**
     * Cannot be executed with unspecified method
     *
     * @return void
     */
    public function testCannotBeExecutedWithUnspecifiedMethod(): void
    {
        $this->expectException(BadMethodCallException::class);

        $command = new BusctlCommand();
        $command->execute();
    }
}