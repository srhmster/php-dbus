# php-dbus

PHP library based on [busctl](https://www.freedesktop.org/software/systemd/man/busctl.html) for interact with DBus

# Basic usage

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

### __construct($service, Marshaller $marshaller = null)
Construct new Dbus service object

Parameters:
- `string $service` - Dbus service name
- `Marshaller|null $marshaller` - Object of data converter, `DbusMarshaller` by 
  default

### call($objectPath, $interface, $method, $properties = null)
Invoke a method and show the response

Parameters:
- `string $objectPath` - Dbus object path
- `string $interface` - Dbus interface name
- `string $method` - Method name
- `string|null $properties` - Properties value, `null` by default

Response: `array|string|int|float|bool|null`

### emit($objectPath, $interface, $signal, $value = null)
Emit a signal

Parameters:
- `string $objectPath` - Dbus object path
- `string $interface` - Dbus interface name
- `string $signal` - Signal name
- `string|null $value` - Signal value, `null` by default

Response: `void`

### getProperty($objectPath, $interface, $property)
Retrieve the current value of object property

Parameters:
- `string $objectPath` - Dbus object path
- `string $interface` - Dbus interface name
- `string $property` - Property name

Response: `array|string|int|float|bool|null`

### setProperty($objectPath, $interface, $property, $value = null)
Set the current value of an object property

Parameters:
- `string $objectPath` - Dbus object path
- `string $interface` - Dbus interface name
- `string $property` - Property name
- `string|null $value` - Property value, `null` by default

Response: `void`