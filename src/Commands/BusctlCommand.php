<?php

namespace Srhmster\PhpDbus\Commands;

use Exception;

/**
 * Busctl console command
 */
class BusctlCommand implements Command
{
    const PREFIX = 'busctl';
    
    /**
     * Command name
     *
     * @var string
     */
    private $name;
    
    /**
     * Command options
     *
     * @var array
     */
    private $options = [];
    
    /**
     * @inheritdoc
     */
    public function setName($value)
    {
        $this->name = $value;
        
        return $this;
    }
    
    /**
     * @inheritdoc
     */
    public function addOption($name, $value = null)
    {
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
     */
    public function addOptions($options)
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
    public function execute($attributes)
    {
        $command = $this->toConsoleCommand($attributes) . ' 2>&1';
        $response = null;
        $code = null;
    
        exec($command, $response, $code);
        if ($code !== 0) {
            throw new Exception($response[0], $code);
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
     * Convert command object to console string
     *
     * @return string
     */
    private function toConsoleCommand($attributes)
    {
        $command = self::PREFIX . ' ';
        foreach ($this->options as $option => $value) {
            $command .= "--$option";
            if (!is_null($value)) {
                $command .= "=$value";
            }
            $command .= " ";
        }
    
        return "$command $this->name " . implode(' ', $attributes);
    }
}