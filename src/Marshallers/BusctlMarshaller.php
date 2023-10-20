<?php

declare(strict_types=1);

namespace Srhmster\PhpDbus\Marshallers;

use Srhmster\PhpDbus\DataObjects\{ArrayDataObject,
    BooleanDataObject,
    BusctlDataObject,
    NumericSignature,
    ObjectPathDataObject,
    StringDataObject,
    VariantDataObject};
use TypeError;

/**
 * Busctl data converter
 */
class BusctlMarshaller implements Marshaller
{
    /**
     * Convert PHP data to Dbus format
     *
     * @param BusctlDataObject|BusctlDataObject[]|null $data
     * @return string|null
     * @throws TypeError
     */
    public function marshal(mixed $data): ?string
    {
        if (!is_null($data)
            && !($data instanceof BusctlDataObject)
            && !is_array($data)
        ) {
            throw new TypeError(
                'A BusctlDataObject::class, array or null data was expected, '
                . 'but a ' . gettype($data) . ' was passed'
            );
        }

        if (is_null($data)) {
            return null;
        }
        
        if ($data instanceof BusctlDataObject) {
            return $data->getValue(true);
        }

        if (count($data) === 0) {
            throw new TypeError('The data cannot be an empty array');
        }

        $signature = '';
        $value = '';
        foreach ($data as $dataObject) {
            if (! $dataObject instanceof BusctlDataObject) {
                throw new TypeError(
                    'A BusctlDataObject::class data item was expected, but a '
                    . gettype($dataObject) . ' was passed'
                );
            }

            $signature .= $dataObject->getSignature();
            $value .= ' ' . $dataObject->getValue();
        }
        
        return $signature . $value;
    }
    
    /**
     * @inheritdoc
     */
    public function unmarshal(
        string $signature,
        array &$data
    ): array|string|int|float|bool|null
    {
        // Unmarshal base types
        if (strlen($signature) === 1) {
            return match ($signature) {
                StringDataObject::SIGNATURE,
                ObjectPathDataObject::SIGNATURE =>
                    str_replace('"', '', array_shift($data)),
                NumericSignature::Byte->value,
                NumericSignature::Int16->value,
                NumericSignature::UInt16->value,
                NumericSignature::Int32->value,
                NumericSignature::UInt32->value,
                NumericSignature::Int64->value,
                NumericSignature::UInt64->value => (int)array_shift($data),
                NumericSignature::Double->value => (float)array_shift($data),
                BooleanDataObject::SIGNATURE => array_shift($data) === 'true',
                VariantDataObject::SIGNATURE =>
                    $this->unmarshal(array_shift($data), $data),
                default => null
            };
        }
        // Unmarshal sequence base types or container types
        $response = [];
        $position = 0;
        while ($position < strlen($signature)) {
            switch ($signature[$position]) {
                case '(':
                    $pEnd = strripos($signature, ')');
                    // Position adjustment for nested structures (i(i(ii)))
                    $subtype = $position === 0
                        ? substr($signature, $position + 1, $pEnd - 1)
                        : substr(
                            $signature,
                            $position + 1,
                            $pEnd - ($position + 1)
                        );
                
                    $value = $this->unmarshal($subtype, $data);
                
                    // Check if type is unique in signature
                    if ($position === 0 && $pEnd === (strlen($signature) - 1)) {
                        $response = $value;
                    } else {
                        $response[] = $value;
                    }
                
                    $position = $pEnd;
                
                    break;
                case ArrayDataObject::SIGNATURE:
                    // Find the type of array elements
                    $stPosition = $this
                        ->findArraySubtypePosition($signature, $position);
                    $subtype = substr(
                        $signature,
                        $stPosition['start'],
                        $stPosition['end'] - ($stPosition['start'] - 1)
                    );
                
                    // Unmarshal array elements
                    $countItems = array_shift($data);
                    $nextChar = $signature[$position + 1];
                
                    $items = [];
                    for ($i = 0; $i < $countItems; $i++) {
                        if ($nextChar === '{') {
                            $key = $this->unmarshal(
                                substr($signature, $stPosition['start'] - 1, 1),
                                $data
                            );
                            $items[$key] = $this->unmarshal($subtype, $data);
                        } else {
                            $items[] = $this->unmarshal($subtype, $data);
                        }
                    }
                
                    // Check if type is unique in signature
                    $stLength = $nextChar === '{'
                        ? strlen($subtype) + 3
                        : strlen($subtype);
                    if (strlen($signature) - 1 === $stLength) {
                        $response = $items;
                    } else {
                        $response[] = $items;
                    }
                
                    $position = $stPosition['end'] + 1;
                
                    break;
                default:
                    $response[] = $this->unmarshal(
                        $signature[$position],
                        $data
                    );
            }
        
            $position++;
        }
    
        return $response;
    }
    
    /**
     * Prepare data for the unmarshalling process
     *
     * Replacing spaces inside string values so that a data array can be
     * formed correctly
     *
     * @param string $data
     * @return array
     */
    public function unmarshallingPrepare(mixed $data): array
    {
        preg_match_all('/\".*\"/U', $data, $matches);
        
        $replaced = [];
        foreach ($matches[0] as $match) {
            if (!preg_match('/\s/', $match)) continue;
    
            $tmp = str_replace(' ', '-', $match);
            $replaced[$tmp] = $match;
            $data = str_replace($match, $tmp, $data);
        }
    
        $preparedData = explode(' ', $data);
        array_walk($preparedData, function (&$value, $key, $replaced) {
            if (isset($replaced[$value])) {
                $value = $replaced[$value];
            }
        }, $replaced);
        
        return $preparedData;
    }
    
    /**
     * Find subtype in array
     *
     * @param string $signature Description of the data structure
     * @param int $pStart Start position in signature
     * @return array
     */
    private function findArraySubtypePosition(
        string $signature,
        int $pStart
    ): array
    {
        $position = [];
        
        switch ($signature[$pStart + 1]) {
            case '(':
                $position['start'] = $pStart + 1;
                $position['end'] = strripos($signature, ')');
                
                break;
            case '{':
                $position['start'] = $pStart + 3;
                $position['end'] = strripos($signature, '}') - 1;
                
                break;
            case ArrayDataObject::SIGNATURE:
                $position['start'] = $pStart + 1;
                $position['end'] = $this->findArraySubtypePosition(
                        $signature,
                        $position['start']
                    )['end'] + 1;
                
                break;
            default:
                $position['start'] = $pStart + 1;
                $position['end'] = $pStart + 1;
        }
        
        return $position;
    }
}