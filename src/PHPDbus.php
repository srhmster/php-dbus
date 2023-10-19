<?php

declare(strict_types=1);

namespace Srhmster\PhpDbus;

use BadMethodCallException;
use RuntimeException;
use Srhmster\PhpDbus\Commands\{BusctlCommand, Command};
use Srhmster\PhpDbus\Marshallers\{BusctlMarshaller, Marshaller};

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
        string $service,
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
     * @param mixed|null $properties
     * @param bool $useSudo
     * @param array $options
     * @return array|string|int|float|bool|null
     * @throws BadMethodCallException|RuntimeException
     */
    public function call(
        string $objectPath,
        string $interface,
        string $method,
        $properties = null,
        bool $useSudo = false,
        array $options = []
    ) {
        $response = $this->command
            ->setMethod(self::CALL)
            ->setUseSudo($useSudo)
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
     * @param mixed|null $value
     * @param bool $useSudo
     * @param array $options
     * @return void
     * @throws BadMethodCallException|RuntimeException
     */
    public function emit(
        string $objectPath,
        string $interface,
        string $signal,
        $value = null,
        bool $useSudo = false,
        array $options = []
    ): void
    {
        $this->command
            ->setMethod(self::EMIT)
            ->setUseSudo($useSudo)
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
     * @param bool $useSudo
     * @param array $options
     * @return array|string|int|float|bool|null
     * @throws BadMethodCallException|RuntimeException
     */
    public function getProperty(
        string $objectPath,
        string $interface,
        string $name,
        bool $useSudo = false,
        array $options = []
    ) {
        $response = $this->command
            ->setMethod(self::GET_PROPERTY)
            ->setUseSudo($useSudo)
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
     * @param mixed|null $value
     * @param bool $useSudo
     * @param array $options
     * @return void
     * @throws BadMethodCallException|RuntimeException
     */
    public function setProperty(
        string $objectPath,
        string $interface,
        string $name,
        $value = null,
        bool $useSudo = false,
        array $options = []
    ): void
    {
        $this->command
            ->setMethod(self::SET_PROPERTY)
            ->setUseSudo($useSudo)
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