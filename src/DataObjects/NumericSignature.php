<?php

declare(strict_types = 1);

namespace Srhmster\PhpDbus\DataObjects;

use UnitEnum;

/**
 * Enum signature for numeric data objects
 */
enum NumericSignature: string
{
    case Byte = 'y';
    case Int16 = 'n';
    case UInt16 = 'q';
    case Int32 = 'i';
    case UInt32 = 'u';
    case Int64 = 'x';
    case UInt64 = 't';
    case Double = 'd';
    
    /**
     * Return only integer signature values
     *
     * @return array
     */
    public static function onlyIntegerValues(): array
    {
        $signatures = self::cases();
    
        if (($key = array_search(self::Double, $signatures)) !== false) {
            unset($signatures[$key]);
        }
        
        return array_map(fn($item) => $item->value, $signatures);
    }
}