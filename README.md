# php-dbus

PHP library for interact with DBus. By default, the console utility 
[busctl](https://www.freedesktop.org/software/systemd/man/busctl.html) is used.

# Basic usage

### Composer
This library is designed for use with the `composer` PHP dependency manager. 
Simply add the `srhmster/php-dbus` package to get started:

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

### call($objectPath, $interface, $method, $properties = null, $useSudo = false, $options = [])
Invoke a method and show the response

Parameters:
- `string $objectPath` - Dbus object path
- `string $interface` - Dbus interface name
- `string $method` - Method name
- `mixed|null $properties` - Properties value, `null` by default
- `bool $useSudo` - Use sudo flag, `false` by default
- `array $options` - Command options, empty array by default

Response: `array|string|int|float|bool|null`

### emit($objectPath, $interface, $signal, $value = null, $useSudo = false, $options = [])
Emit a signal

Parameters:
- `string $objectPath` - Dbus object path
- `string $interface` - Dbus interface name
- `string $signal` - Signal name
- `mixed|null $value` - Signal value, `null` by default
- `bool $useSudo` - Use sudo flag, `false` by default
- `array $options` - Command options, empty array by default

Response: `void`

### getProperty($objectPath, $interface, $name, $useSudo = false, $options = [])
Retrieve the current value of object property

Parameters:
- `string $objectPath` - Dbus object path
- `string $interface` - Dbus interface name
- `string $name` - Property name
- `bool $useSudo` - Use sudo flag, `false` by default
- `array $options` - Command options, empty array by default

Response: `array|string|int|float|bool|null`

### setProperty($objectPath, $interface, $name, $value = null, $useSudo = false, $options = [])
Set the current value of an object property

Parameters:
- `string $objectPath` - Dbus object path
- `string $interface` - Dbus interface name
- `string $name` - Property name
- `mixed|null $value` - Property value, `null` by default
- `bool $useSudo` - Use sudo flag, `false` by default
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

### ::s($value = null)
Create string data object. `Base data type`

Parameters:
- `string|null` $value - Data object value, `null` by default

Response: `StringDataObject`

### ::o($value = null)
Create object path data object. `Base data type`

Parameters:
- `string|null` $value - Data object value, `null` by default

Response: `ObjectPathDataObject`

### ::b($value = null)
Create boolean data object. `Base data type`

Parameters:
- `bool|null` $value - Data object value, `null` by default

Response: `BooleanDataObject`

### ::y($value = null)
Create byte data object. `Base data type`

Parameters:
- `int|null` $value - Data object value, `null` by default

Response: `NumericDataObject`

### ::n($value = null)
Create int16 data object. `Base data type`

Parameters:
- `int|null` $value - Data object value, `null` by default

Response: `NumericDataObject`

### ::q($value = null)
Create uint16 data object. `Base data type`

Parameters:
- `int|null` $value - Data object value, `null` by default

Response: `NumericDataObject`

### ::i($value = null)
Create int32 data object. `Base data type`

Parameters:
- `int|null` $value - Data object value, `null` by default

Response: `NumericDataObject`

### ::u($value = null)
Create uint32 data object. `Base data type`

Parameters:
- `int|null` $value - Data object value, `null` by default

Response: `NumericDataObject`

### ::x($value = null)
Create int64 data object. `Base data type`

Parameters:
- `int|null` $value - Data object value, `null` by default

Response: `NumericDataObject`

### ::t($value = null)
Create uint64 data object. `Base data type`

Parameters:
- `int|null` $value - Data object value, `null` by default

Response: `NumericDataObject`

### ::d($value = null)
Create double data object. `Base data type`

Parameters:
- `float|null` $value - Data object value, `null` by default

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