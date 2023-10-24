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
        string|array &$data,
        ?string $signature = null
    ): array|string|int|float|bool|null
    {
        // Prepare data before unmarshalling
        if (is_string($data)) {
            list($signature, $data) = $this->prepare($data);
            
            return $this->unmarshal($data, $signature);
        }
        
        // Unmarshal base types
        if (strlen($signature) === 1) {
            $item = array_shift($data);
            if (is_null($item)) return null;
            
            return match ($signature) {
                StringDataObject::SIGNATURE,
                ObjectPathDataObject::SIGNATURE => str_replace('"', '', $item),
                NumericSignature::Byte->value,
                NumericSignature::Int16->value,
                NumericSignature::UInt16->value,
                NumericSignature::Int32->value,
                NumericSignature::UInt32->value,
                NumericSignature::Int64->value,
                NumericSignature::UInt64->value => (int)$item,
                NumericSignature::Double->value => (float)$item,
                BooleanDataObject::SIGNATURE => $item === 'true',
                VariantDataObject::SIGNATURE => $this->unmarshal($data, $item),
                default => throw new TypeError('Unknown signature specified')
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
                
                    $value = $this->unmarshal($data, $subtype);
                
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
                                $data,
                                substr($signature, $stPosition['start'] - 1, 1)
                            );
                            $items[$key] = $this->unmarshal($data, $subtype);
                        } else {
                            $items[] = $this->unmarshal($data, $subtype);
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
                    $response[] = $this->unmarshal($data, $signature[$position]);
            }
        
            $position++;
        }
    
        return $response;
    }
    
    /**
     * Prepare data before unmarshalling
     *
     * Replacing spaces inside string values so that a data array can be
     * formed correctly
     *
     * @param string $data
     * @return array
     */
    private function prepare(string $data): array
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
        
        return [
            array_shift($preparedData),
            $preparedData
        ];
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
        return match ($signature[$pStart + 1]) {
            '(' => ['start' => $pStart + 1, 'end' => strripos($signature, ')')],
            '{' => [
                'start' => $pStart + 3,
                'end' => strripos($signature, '}') - 1
            ],
            ArrayDataObject::SIGNATURE => [
                'start' => $pStart + 1,
                'end' => $this->findArraySubtypePosition(
                    $signature,
                    $pStart + 1
                )['end'] + 1,
            ],
            default => ['start' => $pStart + 1, 'end' => $pStart + 1]
        };
    }
}