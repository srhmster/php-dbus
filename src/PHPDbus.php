<?php

namespace Srhmster\PhpDbus;

use BadMethodCallException;
use InvalidArgumentException;
use RuntimeException;
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
     * @throws InvalidArgumentException
     */
    public function __construct(
        $service,
        Marshaller $marshaller = null,
        Command $command = null
    ) {
        if (!is_string($service)) {
            throw new InvalidArgumentException(
                'A string service was expected, but a ' . gettype($service)
                . ' was passed'
            );
        }

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
     * @throws InvalidArgumentException|BadMethodCallException|RuntimeException
     */
    public function call(
        $objectPath,
        $interface,
        $method,
        $properties = null,
        $useSudo = false,
        $options = []
    ) {
        $errorMessage = '';
        if (!$this->validateString($objectPath, 'object path', $errorMessage)
            || !$this->validateString($interface, 'interface', $errorMessage)
            || !$this->validateString($method, 'method', $errorMessage)
        ) {
            throw new InvalidArgumentException($errorMessage);
        }

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
     * @throws InvalidArgumentException|BadMethodCallException|RuntimeException
     */
    public function emit(
        $objectPath,
        $interface,
        $signal,
        $value = null,
        $useSudo = false,
        $options = []
    ) {
        $errorMessage = '';
        if (!$this->validateString($objectPath, 'object path', $errorMessage)
            || !$this->validateString($interface, 'interface', $errorMessage)
            || !$this->validateString($signal, 'signal', $errorMessage)
        ) {
            throw new InvalidArgumentException($errorMessage);
        }

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
     * @throws InvalidArgumentException|BadMethodCallException|RuntimeException
     */
    public function getProperty(
        $objectPath,
        $interface,
        $name,
        $useSudo = false,
        $options = []
    ) {
        $errorMessage = '';
        if (!$this->validateString($objectPath, 'object path', $errorMessage)
            || !$this->validateString($interface, 'interface', $errorMessage)
            || !$this->validateString($name, 'name', $errorMessage)
        ) {
            throw new InvalidArgumentException($errorMessage);
        }

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
     * @throws InvalidArgumentException|BadMethodCallException|RuntimeException
     */
    public function setProperty(
        $objectPath,
        $interface,
        $name,
        $value = null,
        $useSudo = false,
        $options = []
    ) {
        $errorMessage = '';
        if (!$this->validateString($objectPath, 'object path', $errorMessage)
            || !$this->validateString($interface, 'interface', $errorMessage)
            || !$this->validateString($name, 'name', $errorMessage)
        ) {
            throw new InvalidArgumentException($errorMessage);
        }

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

    /**
     * Validate string
     *
     * @param mixed $value
     * @param string $name
     * @param string $message
     * @return bool
     */
    private function validateString($value, $name, &$message)
    {
        if (!is_string($value)) {
            $message = "A string $name was expected, but a " . gettype($value)
                . ' was passed';

            return false;
        }

        return true;
    }
}