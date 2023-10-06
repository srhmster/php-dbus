<?php

namespace Srhmster\PhpDbus\Commands;

use Exception;

/**
 * Console command interface
 */
interface Command
{
    /**
     * Set command name
     *
     * @param string $value
     * @return Command
     */
    public function setName($value);
    
    /**
     * Set a flag to use sudo
     *
     * @param bool $value
     * @return Command
     */
    public function setUseSudo($value);
    
    /**
     * Add option
     *
     * @param string $name
     * @param string|int|bool|null $value
     * @return Command
     */
    public function addOption($name, $value = null);
    
    /**
     * Add options
     *
     * @param array $options
     * @return Command
     */
    public function addOptions($options);
    
    /**
     * Execute command
     *
     * @param array $attributes
     * @return string|array|null
     * @throws Exception
     */
    public function execute($attributes);
    
    /**
     * Convert command object to string with console command
     *
     * @param array $attributes
     * @return string
     */
    public function toString($attributes);
}