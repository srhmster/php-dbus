<?php

declare(strict_types=1);

namespace Srhmster\PhpDbus\Tests;

use PHPUnit\Framework\TestCase;
use Srhmster\PhpDbus\DataObjects\{BusctlDataObject, StringDataObject};
use TypeError;

/**
 * StringDataObject class tests
 */
final class StringDataObjectTest extends TestCase
{
    /**
     * Valid data provider
     *
     * @return array
     */
    public function validDataProvider(): array
    {
        return [
            ['string', ['value' => '"string"']],
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
            [123],
            [12.3],
            [true],
            [[]],
            [BusctlDataObject::s('string')],
        ];
    }
    
    /**
     * Can be created from valid value
     *
     * @dataProvider validDataProvider
     *
     * @param string|null $value
     * @param array $expected
     * @return void
     */
    public function testCanBeCreatedFromValidValue(
        ?string $value,
        array $expected
    ): void
    {
        $object = BusctlDataObject::s($value);
        
        $this->assertInstanceOf(StringDataObject::class, $object);
        $this->assertEquals($expected['value'], $object->getValue());
        $this->assertEquals(
            StringDataObject::SIGNATURE . ' ' . $expected['value'],
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
        
        BusctlDataObject::s($value);
    }
}