<?php

namespace PhpDbus;

use Exception;

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
     * Constructor
     *
     * @param string $service
     */
    public function __construct($service)
    {
        $this->service = $service;
    }
    
    /**
     * Invoke a method and show the response
     *
     * @param string $objectPath
     * @param string $interface
     * @param string $method
     * @param string|null $properties
     * @return string|null
     * @throws Exception
     */
    public function call($objectPath, $interface, $method, $properties = null)
    {
        return $this->execute(sprintf(
            'busctl call %s %s %s %s %s',
            $this->service,
            $objectPath,
            $interface,
            $method,
            $properties
        ));
    }
    
    /**
     * Emit a signal
     *
     * @param string $objectPath
     * @param string $interface
     * @param string $signal
     * @param string|null $value
     * @return void
     * @throws Exception
     */
    public function emit($objectPath, $interface, $signal, $value = null)
    {
        $this->execute(sprintf(
            'busctl emit %s %s %s %s %s',
            $this->service,
            $objectPath,
            $interface,
            $signal,
            $value
        ));
    }
    
    /**
     * Retrieve the current value of object property
     *
     * @param string $objectPath
     * @param string $interface
     * @param string $property
     * @return string|null
     * @throws Exception
     */
    public function getProperty($objectPath, $interface, $property)
    {
        return $this->execute(sprintf(
            'busctl get-property %s %s %s %s',
            $this->service,
            $objectPath,
            $interface,
            $property
        ));
    }
    
    /**
     * Set the current value of an object property
     *
     * @param string $objectPath
     * @param string $interface
     * @param string $property
     * @param string|null $value
     * @return void
     * @throws Exception
     */
    public function setProperty($objectPath, $interface, $property, $value = null)
    {
        $this->execute(sprintf(
            'busctl set-property %s %s %s %s %s',
            $this->service,
            $objectPath,
            $interface,
            $property,
            $value
        ));
    }
    
    /**
     * Execute console command
     *
     * @param string $command
     * @return string|null
     * @throws Exception
     */
    private function execute($command)
    {
        $response = null;
        $code = null;
        
        exec($command . ' 2>&1 ', $response, $code);
        if ($code !== 0) {
            throw new Exception($response[0], $code);
        }
        
        if (isset($response) && count($response) > 0) {
            if (count($response) === 1) {
                $response = $response[0];
            }
        } else {
            $response = null;
        }
        
        return $response;
    }
}