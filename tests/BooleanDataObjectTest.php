<?php

declare(strict_types=1);

namespace Srhmster\PhpDbus\Tests;

use PHPUnit\Framework\TestCase;
use Srhmster\PhpDbus\DataObjects\{BooleanDataObject, BusctlDataObject};
use TypeError;

/**
 * BooleanDataObject class tests
 */
final class BooleanDataObjectTest extends TestCase
{
    /**
     * Valid data provider
     *
     * @return array
     */
    public function validDataProvider(): array
    {
        return [
            [true, ['value' => 'true']],
            [false, ['value' => 'false']],
            [null, ['value' => null]],
        ];
    }
    
    /**
     * Invalid data provider
     *
     * @return array
     */
    public function invalidDataProvider(): array
    {
        return [
            ['string'],
            [123],
            [12.3],
            [[]],
            [BusctlDataObject::b(true)]
        ];
    }
    
    /**
     * Can be created from valid value
     *
     * @dataProvider validDataProvider
     *
     * @param bool|null $value
     * @param array $expected
     * @return void
     */
    public function testCanBeCreatedFromValidValue(
        ?bool $value,
        array $expected
    ): void
    {
        $object = BusctlDataObject::b($value);
        
        $this->assertInstanceOf(BooleanDataObject::class, $object);
        $this->assertEquals($expected['value'], $object->getValue());
        $this->assertEquals(
            BooleanDataObject::SIGNATURE . ' ' . $expected['value'],
            $object->getValue(true)
        );
    }
    
    /**
     * Cannot be created from invalid value
     *
     * @dataProvider invalidDataProvider
     *
     * @param mixed $value
     * @return void
     */
    public function testCannotBeCreatedFromInvalidValue($value): void
    {
        $this->expectException(TypeError::class);
        
        BusctlDataObject::b($value);
    }
}