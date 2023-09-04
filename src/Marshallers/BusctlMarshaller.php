<?php

namespace Srhmster\PhpDbus\Marshallers;

/**
 * Busctl data converter
 */
class BusctlMarshaller implements Marshaller
{
    /**
     * @inheritdoc
     */
    public function marshal($data)
    {
        return $data;
    }
    
    /**
     * @inheritdoc
     */
    public function unmarshal($signature, &$data)
    {
        // Unmarshal base types
        if (strlen($signature) === 1) {
            $response = null;
        
            switch ($signature) {
                case 's': // string
                case 'o': // object path
                    $response = str_replace('"', '', array_shift($data));
                    break;
                case 'y': // byte
                case 'n': // int16
                case 'q': // uint16
                case 'i': // int32
                case 'u': // uint32
                case 'x': // int64
                case 't': // uint64
                case 'd': // double
                    $response = array_shift($data) * 1;
                    break;
                case 'b': // bool
                    $response = array_shift($data) === 'true';
                    break;
                case 'v': // variant
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
                case 'a':
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
            case 'a':
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