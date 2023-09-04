<?php

namespace Srhmster\PhpDbus;

use Exception;
use Srhmster\PhpDbus\Commands\BusctlCommand;
use Srhmster\PhpDbus\Commands\Command;
use Srhmster\PhpDbus\Marshallers\BusctlMarshaller;
use Srhmster\PhpDbus\Marshallers\Marshaller;

/**
 * PHP Dbus library main class
 */
class PHPDbus
{
    // Method names
    const CALL = 'call';
    const EMIT = 'emit';
    const GET_PROPERTY = 'get-property';
    const SET_PROPERTY = 'set-property';

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
            ->setName(self::CALL)
            ->addOptions($options)
            ->execute([
                $this->service,
                $objectPath,
                $interface,
                $method,
                $this->marshaller->marshal($properties)
            ]);

        if (!is_null($response)) {
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
            ->setName(self::EMIT)
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
            ->setName(self::GET_PROPERTY)
            ->addOptions($options)
            ->execute([
                $this->service,
                $objectPath,
                $interface,
                $name
            ]);
        
        if (!is_null($response)) {
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
            ->setName(self::SET_PROPERTY)
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