<?php

namespace Srhmster\PhpDbus\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Srhmster\PhpDbus\DataObjects\BusctlDataObject;
use Srhmster\PhpDbus\DataObjects\ObjectPathDataObject;

/**
 * ObjectPathDataObject class tests
 */
class ObjectPathDataObjectTest extends TestCase
{
    /**
     * Valid data provider
     *
     * @return array
     */
    public function validDataProvider()
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
    public function invalidDataProvider()
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
    public function testCanBeCreatedFromValidValue($value, $expected)
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
    public function testCannotBeCreatedFromInvalidValue($value)
    {
        $this->expectException(InvalidArgumentException::class);
        
        BusctlDataObject::o($value);
    }
}