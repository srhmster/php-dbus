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

# Examples
Files with examples of using the library can be found in the `examples` 
directory

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
- `mixed|null $properties` - Properties value, `null` by default
- `array $options` - Command options, empty array by default

Response: `array|string|int|float|bool|null`

### emit($objectPath, $interface, $signal, $value = null, $options = [])
Emit a signal

Parameters:
- `string $objectPath` - Dbus object path
- `string $interface` - Dbus interface name
- `string $signal` - Signal name
- `mixed|null $value` - Signal value, `null` by default
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
- `mixed|null $value` - Property value, `null` by default
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

# BusctlDataObject
This is a set of classes for convenient work with data in PHP. The 
`BusctlMarshaller` can convert this data into the correct Dbus format. Use the 
base class `BusctlDataObject` static methods described below to create a data 
object with the desired data type.

## Available methods

### ::s($value)
Create string data object. `Base data type`

Parameters:
- `string` $value

Response: `StringDataObject`

### ::o($value)
Create object path data object. `Base data type`

Parameters:
- `string` $value

Response: `ObjectPathDataObject`

### ::b($value)
Create boolean data object. `Base data type`

Parameters:
- `bool` $value

Response: `BooleanDataObject`

### ::y($value)
Create byte data object. `Base data type`

Parameters:
- `int` $value

Response: `NumericDataObject`

### ::n($value)
Create int16 data object. `Base data type`

Parameters:
- `int` $value

Response: `NumericDataObject`

### ::q($value)
Create uint16 data object. `Base data type`

Parameters:
- `int` $value

Response: `NumericDataObject`

### ::i($value)
Create int32 data object. `Base data type`

Parameters:
- `int` $value

Response: `NumericDataObject`

### ::u($value)
Create uint32 data object. `Base data type`

Parameters:
- `int` $value

Response: `NumericDataObject`

### ::x($value)
Create int64 data object. `Base data type`

Parameters:
- `int` $value

Response: `NumericDataObject`

### ::t($value)
Create uint64 data object. `Base data type`

Parameters:
- `int` $value

Response: `NumericDataObject`

### ::d($value)
Create double data object. `Base data type`

Parameters:
- `float` $value

Response: `NumericDataObject`

### ::v(BusctlDataObject $value)
Create variant data object. `Container data type`

Parameters:
- `BusctlDataObject` $value - Any data object other than a VariantDataObject

Response: `VariantDataObject`

### ::r($value)
Create struct data object. `Container data type`

Parameters:
- `BusctlDataObject|BusctlDataObject[]` $value

Response: `StructDataObject`

### ::a($value)
Create array data object. `Container data type`

Parameters:
- `BusctlDataObject[]` $value

Response: `ArrayDataObject`

### ::e($value)
Create map data object. `Container data type`

Parameters:
- `BusctlDataObject[][]` $value - Each element of the array must be in the 
format `['key' => BusctlDataObject, 'value' => BusctlDataObject]`. Key value
can only be a base data type.

Response: `MapDataObject`