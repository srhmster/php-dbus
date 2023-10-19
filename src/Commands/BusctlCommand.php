<?php

declare(strict_types=1);

namespace Srhmster\PhpDbus\Commands;

use BadMethodCallException;
use RuntimeException;
use TypeError;

/**
 * Busctl console command
 */
class BusctlCommand implements Command
{
    const PREFIX = 'busctl';
    
    /**
     * Command method
     *
     * @var string
     */
    private $method;
    
    /**
     * Sudo use flag
     *
     * @var bool
     */
    private $useSudo = false;
    
    /**
     * Command options
     *
     * @var array
     */
    private $options = [];

    /**
     * @inheritdoc
     */
    public function setMethod(string $value): Command
    {
        $this->method = $value;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setUseSudo(bool $value): Command
    {
        $this->useSudo = $value;
        
        return $this;
    }
    
    /**
     * @inheritdoc
     */
    public function addOption(string $name, $value = null): Command
    {
        if (!is_string($value)
            && !is_numeric($value)
            && !is_bool($value)
            && !is_null($value)
        ) {
            throw new TypeError(
                'A string, numeric, boolean or null option value was expected, '
                . 'but a ' . gettype($value) . ' was passed'
            );
        }

        $this->options[$name] = $value;
        
        return $this;
    }
    
    /**
     * Add options
     *
     * If the option does not require a value, then specify only its name
     *
     * [
     *   'option_name_1',
     *   'option_name_2',
     * ]
     *
     * otherwise specify an array
     *
     * [
     *   ['option_name_1', 'option_value_1'],
     *   ['option_name_2', 'option_value_2'],
     * ]
     *
     * @param array $options
     * @return Command
     * @throws TypeError
     */
    public function addOptions(array $options): Command
    {
        foreach ($options as $option) {
            if (is_array($option) && isset($option[0]) && isset($option[1])) {
                $this->addOption($option[0], $option[1]);
            } else {
                $this->addOption($option);
            }
        }
        
        return $this;
    }
    
    /**
     * @inheritdoc
     */
    public function execute(array $attributes = [])
    {
        $response = null;
        $code = 0;
    
        exec($this->toString($attributes) . ' 2>&1', $response, $code);
        if ($code !== 0) {
            throw new RuntimeException($response[0], $code);
        }
    
        if (isset($response) && count($response) > 0) {
            if (count($response) === 1) {
                $response = $response[0];
            }
            
            return $response;
        }
    
        return null;
    }
    
    /**
     * @inheritdoc
     */
    public function toString(array $attributes = []): string
    {
        if (!isset($this->method)) {
            throw new BadMethodCallException("Method command not specified");
        }

        $command = $this->useSudo ? 'sudo ' : '';
        $command .= self::PREFIX;
        foreach ($this->options as $option => $value) {
            $command .= " --$option";
            if (!is_null($value)) {
                $command .= "=$value";
            }
        }

        $command .= ' ' . $this->method;

        return count($attributes) === 0
            ? $command
            : $command . ' ' . implode(' ', $attributes);
    }
}