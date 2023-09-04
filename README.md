# php-dbus

PHP library for interact with DBus. By default, the console utility 
[busctl](https://www.freedesktop.org/software/systemd/man/busctl.html) is used.

# Basic usage

### Composer
This library is designed for use with the `composer` PHP dependency manager. Simply add the `srhmster/php-dbus` package 
to get started:

````
composer require srhmster/php-dbus
````

### Manually
If you don't have composer available, then simply download the code

````
git clone https://github.com/srhmster/php-dbus vendor/srhmster/php-dbus
````

and include `autoload.php`

````php
<?php

require_once __DIR__ . '/vendor/srhmster/php-dbus/autoload.php';
````

# Available methods

### __construct($service, Marshaller $marshaller = null, Command $command = null)
Construct new Dbus service object

Parameters:
- `string $service` - Dbus service name
- `Marshaller|null $marshaller` - Object of data converter, `BusctlMarshaller` by 
  default
- `Command|null $command` - Object of console command, `BusctlCommand` by default

### call($objectPath, $interface, $method, $properties = null, $options = [])
Invoke a method and show the response

Parameters:
- `string $objectPath` - Dbus object path
- `string $interface` - Dbus interface name
- `string $method` - Method name
- `string|null $properties` - Properties value, `null` by default
- `array $options` - Command options, empty array by default

Response: `array|string|int|float|bool|null`

### emit($objectPath, $interface, $signal, $value = null, $options = [])
Emit a signal

Parameters:
- `string $objectPath` - Dbus object path
- `string $interface` - Dbus interface name
- `string $signal` - Signal name
- `string|null $value` - Signal value, `null` by default
- `array $options` - Command options, empty array by default

Response: `void`

### getProperty($objectPath, $interface, $name, $options = [])
Retrieve the current value of object property

Parameters:
- `string $objectPath` - Dbus object path
- `string $interface` - Dbus interface name
- `string $name` - Property name
- `array $options` - Command options, empty array by default

Response: `array|string|int|float|bool|null`

### setProperty($objectPath, $interface, $name, $value = null, $options = [])
Set the current value of an object property

Parameters:
- `string $objectPath` - Dbus object path
- `string $interface` - Dbus interface name
- `string $name` - Property name
- `string|null $value` - Property value, `null` by default
- `array $options` - Command options, empty array by default

Response: `void`

# Command options format

If the option does not require a value, then specify only its name
````php
$options = [
    'option_name_1',
    'option_name_2',
];
````
otherwise specify an array
````php
$options = [
    ['option_name_1', 'option_value_1'],
    ['option_name_2', 'option_value_2'],
];
````