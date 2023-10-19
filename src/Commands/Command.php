<?php

declare(strict_types=1);

namespace Srhmster\PhpDbus\Commands;

use BadMethodCallException;
use RuntimeException;
use TypeError;

/**
 * Console command interface
 */
interface Command
{
    /**
     * Set command method
     *
     * @param string $value
     * @return Command
     */
    public function setMethod(string $value): Command;

    /**
     * Set a flag to use sudo
     *
     * @param bool $value
     * @return Command
     */
    public function setUseSudo(bool $value): Command;

    /**
     * Add option
     *
     * @param string $name
     * @param string|int|bool|null $value
     * @return Command
     * @throws TypeError
     */
    public function addOption(string $name, $value = null): Command;

    /**
     * Add options
     *
     * @param array $options
     * @return Command
     * @throws TypeError
     */
    public function addOptions(array $options): Command;

    /**
     * Execute command
     *
     * @param array $attributes
     * @return string|array|null
     * @throws BadMethodCallException|RuntimeException
     */
    public function execute(array $attributes = []);

    /**
     * Convert command object to string with console command
     *
     * @param array $attributes
     * @return string
     * @throws BadMethodCallException
     */
    public function toString(array $attributes = []): string;
}