<?php

declare(strict_types=1);

use Srhmster\PhpDbus\DataObjects\BusctlDataObject;
use Srhmster\PhpDbus\PHPDbus;

require_once __DIR__ . '/vendor/autoload.php';

$dbus = new PHPDbus('org.freedesktop.NetworkManager');

// Get object path to network devices
$devices = $dbus->getProperty(
    '/org/freedesktop/NetworkManager',
    'org.freedesktop.NetworkManager',
    'Devices'
);

// Get object path to first device active connection
$activeConnection = $dbus->getProperty(
    $devices[0],
    'org.freedesktop.NetworkManager.Device',
    'ActiveConnection'
);

// Get object path to settings connection
$settingsConnection = $dbus->getProperty(
    $activeConnection,
    'org.freedesktop.NetworkManager.Connection.Active',
    'Connection'
);

// Get settings
$settings = $dbus->call(
    $settingsConnection,
    'org.freedesktop.NetworkManager.Settings.Connection',
    'GetSettings'
);

// Update settings
$dbus->call(
    $settingsConnection,
    'org.freedesktop.NetworkManager.Settings.Connection',
    'Update',
    // Data format is a{sa{sv}}
    BusctlDataObject::e([
        [
            'key' => BusctlDataObject::s('connection'),
            'value' => BusctlDataObject::e([
                [
                    'key' => BusctlDataObject::s('id'),
                    'value' => BusctlDataObject::v(
                        BusctlDataObject::s($settings['connection']['id'])
                    )
                ],
                [
                    'key' => BusctlDataObject::s('type'),
                    'value' => BusctlDataObject::v(
                        BusctlDataObject::s($settings['connection']['type'])
                    )
                ],
                [
                    'key' => BusctlDataObject::s('uuid'),
                    'value' => BusctlDataObject::v(
                        BusctlDataObject::s($settings['connection']['uuid'])
                    )
                ]
            ])
        ],
        [
            'key' => BusctlDataObject::s('ipv4'),
            'value' => BusctlDataObject::e([
                [
                    'key' => BusctlDataObject::s('address-data'),
                    'value' => BusctlDataObject::v(
                        // Data format is aa{sv}
                        BusctlDataObject::a([
                            BusctlDataObject::e([
                                [
                                    'key' => BusctlDataObject::s('address'),
                                    'value' => BusctlDataObject::v(
                                        BusctlDataObject::s('192.168.2.181')
                                    )
                                ],
                                [
                                    'key' => BusctlDataObject::s('prefix'),
                                    'value' => BusctlDataObject::v(
                                        BusctlDataObject::u(24)
                                    )
                                ]
                            ])
                        ])
                    )
                ],
                [
                    'key' => BusctlDataObject::s('gateway'),
                    'value' => BusctlDataObject::v(
                        BusctlDataObject::s('192.168.2.1')
                    )
                ],
                [
                    'key' => BusctlDataObject::s('method'),
                    'value' => BusctlDataObject::v(
                        BusctlDataObject::s('manual')
                    )
                ],
                [
                    'key' => BusctlDataObject::s('dns'),
                    'value' => BusctlDataObject::v(
                        // Empty DNS values
                        BusctlDataObject::a([
                            BusctlDataObject::u()
                        ])
                    )
                ]
            ])
        ]
    ])
);