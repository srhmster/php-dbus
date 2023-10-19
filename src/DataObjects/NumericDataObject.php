<?php

declare(strict_types=1);

namespace Srhmster\PhpDbus\DataObjects;

use TypeError;

/**
 * Numeric busctl data object
 */
class NumericDataObject extends BusctlDataObject
{
    const BYTE_SIGNATURE = 'y';
    const INT16_SIGNATURE = 'n';
    const UINT16_SIGNATURE = 'q';
    const INT32_SIGNATURE = 'i';
    const UINT32_SIGNATURE = 'u';
    const INT64_SIGNATURE = 'x';
    const UINT64_SIGNATURE = 't';
    const DOUBLE_SIGNATURE = 'd';
    
    /**
     * Constructor
     *
     * @param string $signature
     * @param int|float|null $value
     * @throws TypeError
     */
    public function __construct(string $signature, $value = null)
    {
        if (!$this->validateSignature($signature)) {
            throw new TypeError(
                'One of the signature values ('
                . implode(',', $this->getValidSignatures())
                . ') was expected, but ' . $signature . ' was passed'
            );
        }
        
        if (!$this->validateValue($value)) {
            throw new TypeError(
                'A numeric or null value was expected, but ' . gettype($value)
                . ' was passed'
            );
        }
        
        $this->signature = $signature;
        $this->value = $value;
    }
    
    /**
     * @inheritDoc
     */
    public function getValue(bool $withSignature = false): ?string
    {
        return $withSignature === true
            ? $this->signature . ' ' . $this->value
            : (string)$this->value;
    }
    
    /**
     * Get valid numeric signatures
     *
     * @return string[]
     */
    private function getValidSignatures(): array
    {
        return [
            self::BYTE_SIGNATURE,
            self::INT16_SIGNATURE,
            self::UINT16_SIGNATURE,
            self::INT32_SIGNATURE,
            self::UINT32_SIGNATURE,
            self::INT64_SIGNATURE,
            self::UINT64_SIGNATURE,
            self::DOUBLE_SIGNATURE,
        ];
    }
    
    /**
     * Check the correctness of the specified signature
     *
     * @param string $signature
     * @return bool
     */
    private function validateSignature(string $signature): bool
    {
        return in_array($signature, $this->getValidSignatures());
    }
    
    /**
     * Check the correctness of the specified value
     *
     * @param mixed $value
     * @return bool
     */
    private function validateValue($value): bool
    {
        return is_null($value) || is_numeric($value);
    }
}