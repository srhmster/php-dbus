<?php

namespace Srhmster\PhpDbus\Commands;

use BadMethodCallException;
use InvalidArgumentException;
use RuntimeException;

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
     * @throws InvalidArgumentException
     */
    public function setMethod($value);

    /**
     * Set a flag to use sudo
     *
     * @param bool $value
     * @return Command
     * @throws InvalidArgumentException
     */
    public function setUseSudo($value);
    
    /**
     * Add option
     *
     * @param string $name
     * @param string|int|bool|null $value
     * @return Command
     * @throws InvalidArgumentException
     */
    public function addOption($name, $value = null);
    
    /**
     * Add options
     *
     * @param array $options
     * @return Command
     * @throws InvalidArgumentException
     */
    public function addOptions($options);
    
    /**
     * Execute command
     *
     * @param array $attributes
     * @return string|array|null
     * @throws InvalidArgumentException|BadMethodCallException|RuntimeException
     */
    public function execute($attributes = []);
    
    /**
     * Convert command object to string with console command
     *
     * @param array $attributes
     * @return string
     * @throws BadMethodCallException
     */
    public function toString($attributes = []);
}