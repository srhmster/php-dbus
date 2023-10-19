<?php

declare(strict_types=1);

namespace Srhmster\PhpDbus\Tests;

use PHPUnit\Framework\TestCase;
use Srhmster\PhpDbus\DataObjects\{BusctlDataObject, ObjectPathDataObject};
use TypeError;

/**
 * ObjectPathDataObject class tests
 */
final class ObjectPathDataObjectTest extends TestCase
{
    /**
     * Valid data provider
     *
     * @return array
     */
    public function validDataProvider(): array
    {
        return [
            ['/', ['value' => '/']],
            ['/path/to/object', ['value' => '/path/to/object']],
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
            ['/path/to/object/'],
            ['path/to/object'],
            ['/path/to/new.object'],
            [123],
            [12.3],
            [true],
            [[]],
            [BusctlDataObject::o('/object/path')]
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
        $object = BusctlDataObject::o($value);
        
        $this->assertInstanceOf(ObjectPathDataObject::class, $object);
        $this->assertEquals($expected['value'], $object->getValue());
        $this->assertEquals(
            ObjectPathDataObject::SIGNATURE . ' ' . $expected['value'],
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
        
        BusctlDataObject::o($value);
    }
}