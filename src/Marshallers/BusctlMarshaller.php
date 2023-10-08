<?php

namespace Srhmster\PhpDbus\Marshallers;

use InvalidArgumentException;
use Srhmster\PhpDbus\DataObjects\ArrayDataObject;
use Srhmster\PhpDbus\DataObjects\BooleanDataObject;
use Srhmster\PhpDbus\DataObjects\BusctlDataObject;
use Srhmster\PhpDbus\DataObjects\NumericDataObject;
use Srhmster\PhpDbus\DataObjects\ObjectPathDataObject;
use Srhmster\PhpDbus\DataObjects\StringDataObject;
use Srhmster\PhpDbus\DataObjects\VariantDataObject;

/**
 * Busctl data converter
 */
class BusctlMarshaller implements Marshaller
{
    /**
     * Convert PHP data to Dbus format
     *
     * @param BusctlDataObject|BusctlDataObject[]|null $data
     * @return string
     * @throws InvalidArgumentException
     */
    public function marshal($data)
    {
        if (!is_null($data)
            && !($data instanceof BusctlDataObject)
            && !is_array($data)
        ) {
            throw new InvalidArgumentException(
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
            throw new InvalidArgumentException(
                'The data cannot be an empty array'
            );
        }

        $signature = '';
        $value = '';
        foreach ($data as $dataObject) {
            if (! $dataObject instanceof BusctlDataObject) {
                throw new InvalidArgumentException(
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
    public function unmarshal($signature, &$data)
    {
        if (!is_string($signature)) {
            throw new InvalidArgumentException(
                'A string signature was expected, but a ' . gettype($signature)
                . ' was passed'
            );
        }

        if (!is_array($data)) {
            throw new InvalidArgumentException(
                'A array data was expected, but a ' . gettype($data)
                . ' was passed'
            );
        }

        // Unmarshal base types
        if (strlen($signature) === 1) {
            $response = null;
        
            switch ($signature) {
                case StringDataObject::SIGNATURE:
                case ObjectPathDataObject::SIGNATURE:
                    $response = str_replace('"', '', array_shift($data));
                    break;
                case NumericDataObject::BYTE_SIGNATURE:
                case NumericDataObject::INT16_SIGNATURE:
                case NumericDataObject::UINT16_SIGNATURE:
                case NumericDataObject::INT32_SIGNATURE:
                case NumericDataObject::UINT32_SIGNATURE:
                case NumericDataObject::INT64_SIGNATURE:
                case NumericDataObject::UINT64_SIGNATURE:
                case NumericDataObject::DOUBLE_SIGNATURE:
                    $response = array_shift($data) * 1;
                    break;
                case BooleanDataObject::SIGNATURE:
                    $response = array_shift($data) === 'true';
                    break;
                case VariantDataObject::SIGNATURE:
                    $response = $this->unmarshal(array_shift($data), $data);
                    break;
            }
        
            return $response;
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
     * Find subtype in array
     *
     * @param string $signature Description of the data structure
     * @param int $pStart Start position in signature
     * @return array
     */
    private function findArraySubtypePosition($signature, $pStart)
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