<?php

namespace PhpDbus;

use Exception;
use PhpDbus\Commands\BusctlCommand;
use PhpDbus\Commands\Command;
use PhpDbus\Marshallers\BusctlMarshaller;
use PhpDbus\Marshallers\Marshaller;

/**
 * PHP Dbus library main class
 */
class PHPDbus
{
    /**
     * Dbus service name
     *
     * @var string
     */
    private $service;
    
    /**
     * Data converter
     *
     * @var Marshaller
     */
    private $marshaller;
    
    /**
     * Console command
     *
     * @var Command
     */
    private $command;
    
    /**
     * Constructor
     *
     * @param string $service
     * @param Marshaller|null $marshaller Object of data converter
     * @param Command|null $command Object of console command
     */
    public function __construct(
        $service,
        Marshaller $marshaller = null,
        Command $command = null
    ) {
        $this->service = $service;
        $this->marshaller = $marshaller ?: new BusctlMarshaller();
        $this->command = $command ?: new BusctlCommand();
    }
    
    /**
     * Invoke a method and show the response
     *
     * @param string $objectPath
     * @param string $interface
     * @param string $method
     * @param string|null $properties
     * @param array $options
     * @return array|string|int|float|bool|null
     * @throws Exception
     */
    public function call(
        $objectPath,
        $interface,
        $method,
        $properties = null,
        $options = []
    ) {
        $response = $this->command
            ->setName('call')
            ->addOptions($options)
            ->execute([
                $this->service,
                $objectPath,
                $interface,
                $method,
                $this->marshaller->marshal($properties)
            ]);
        
        if (!is_null($response) && count($response) > 1) {
            $data = explode(' ', $response);
            $signature = array_shift($data);
            
            return $this->marshaller->unmarshal($signature, $data);
        }
        
        return null;
    }
    
    /**
     * Emit a signal
     *
     * @param string $objectPath
     * @param string $interface
     * @param string $signal
     * @param string|null $value
     * @param array $options
     * @return void
     * @throws Exception
     */
    public function emit(
        $objectPath,
        $interface,
        $signal,
        $value = null,
        $options = []
    ) {
        $this->command
            ->setName('emit')
            ->addOptions($options)
            ->execute([
                $this->service,
                $objectPath,
                $interface,
                $signal,
                $this->marshaller->marshal($value)
            ]);
    }
    
    /**
     * Retrieve the current value of object property
     *
     * @param string $objectPath
     * @param string $interface
     * @param string $name
     * @param array $options
     * @return array|string|int|float|bool|null
     * @throws Exception
     */
    public function getProperty(
        $objectPath,
        $interface,
        $name,
        $options = []
    ) {
        $response = $this->command
            ->setName('get-property')
            ->addOptions($options)
            ->execute([
                $this->service,
                $objectPath,
                $interface,
                $name
            ]);
        
        if (!is_null($response) && count($response) > 1) {
            $data = explode(' ', $response);
            $signature = array_shift($data);
            
            return $this->marshaller->unmarshal($signature, $data);
        }
        
        return null;
    }
    
    /**
     * Set the current value of an object property
     *
     * @param string $objectPath
     * @param string $interface
     * @param string $name
     * @param string|null $value
     * @param array $options
     * @return void
     * @throws Exception
     */
    public function setProperty(
        $objectPath,
        $interface,
        $name,
        $value = null,
        $options = []
    ) {
        $this->command
            ->setName('set-property')
            ->addOptions($options)
            ->execute([
                $this->service,
                $objectPath,
                $interface,
                $name,
                $this->marshaller->marshal($value)
            ]);
    }
}