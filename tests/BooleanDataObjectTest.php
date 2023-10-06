<?php

namespace Srhmster\PhpDbus\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Srhmster\PhpDbus\DataObjects\BooleanDataObject;
use Srhmster\PhpDbus\DataObjects\BusctlDataObject;

/**
 * BooleanDataObject class tests
 */
class BooleanDataObjectTest extends TestCase
{
    /**
     * Valid data provider
     *
     * @return array
     */
    public function validDataProvider()
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
    public function invalidDataProvider()
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
    public function testCanBeCreatedFromValidValue($value, $expected)
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
    public function testCannotBeCreatedFromInvalidValue($value)
    {
        $this->expectException(InvalidArgumentException::class);
        
        BusctlDataObject::b($value);
    }
}