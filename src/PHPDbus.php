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
     * Data converter
     *
     * @var Marshaller
     */
    private $marshaller;
    
    /**
     * Constructor
     *
     * @param string $service
     * @param Marshaller|null $marshaller Object of data converter
     */
    public function __construct($service, Marshaller $marshaller = null)
    {
        $this->service = $service;
        $this->marshaller = $marshaller ?: new DbusMarshaller();
    }
    
    /**
     * Invoke a method and show the response
     *
     * @param string $objectPath
     * @param string $interface
     * @param string $method
     * @param string|null $properties
     * @return array|string|int|float|bool|null
     * @throws Exception
     */
    public function call($objectPath, $interface, $method, $properties = null)
    {
        $response = $this->execute(sprintf(
            'busctl call %s %s %s %s %s',
            $this->service,
            $objectPath,
            $interface,
            $method,
            $this->marshaller->marshal($properties)
        ));
        
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
            $this->marshaller->marshal($value)
        ));
    }
    
    /**
     * Retrieve the current value of object property
     *
     * @param string $objectPath
     * @param string $interface
     * @param string $property
     * @return array|string|int|float|bool|null
     * @throws Exception
     */
    public function getProperty($objectPath, $interface, $property)
    {
        $response = $this->execute(sprintf(
            'busctl get-property %s %s %s %s',
            $this->service,
            $objectPath,
            $interface,
            $property
        ));
        
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
            $this->marshaller->marshal($value)
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