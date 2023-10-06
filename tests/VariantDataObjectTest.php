<?php

namespace Srhmster\PhpDbus\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_Error;
use Srhmster\PhpDbus\DataObjects\BusctlDataObject;
use Srhmster\PhpDbus\DataObjects\VariantDataObject;

/**
 * VariantDataObject class tests
 */
class VariantDataObjectTest extends TestCase
{
    /**
     * Valid data provider
     *
     * @return array
     */
    public function validDataProvider()
    {
        return [
            [BusctlDataObject::s(), ['value' => null]],
            [
                BusctlDataObject::s('hello world'),
                ['value' => 's "hello world"']
            ],
            [
                BusctlDataObject::a([
                    BusctlDataObject::s('hello'),
                    BusctlDataObject::s('world')
                ]),
                ['value' => 'as 2 "hello" "world"']
            ]
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
            [true],
            [null],
            [[]],
        ];
    }
    
    /**
     * Can be created from valid value
     *
     * @dataProvider validDataProvider
     *
     * @param BusctlDataObject $value
     * @param array $expected
     * @return void
     */
    public function testCanBeCreatedFromValidValue($value, $expected)
    {
        $object = BusctlDataObject::v($value);
        
        $this->assertInstanceOf(VariantDataObject::class, $object);
        $this->assertEquals($expected['value'], $object->getValue());
        $this->assertEquals(
            VariantDataObject::SIGNATURE . ' ' . $expected['value'],
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
        $this->expectException(PHPUnit_Framework_Error::class);
        
        BusctlDataObject::v($value);
    }
}