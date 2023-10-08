<?php

namespace Srhmster\PhpDbus\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Srhmster\PhpDbus\DataObjects\BusctlDataObject;
use Srhmster\PhpDbus\Marshallers\BusctlMarshaller;
use Srhmster\PhpDbus\Marshallers\Marshaller;
use stdClass;

/**
 * BusctlMarshaller class tests
 */
class BusctlMarshallerTest extends TestCase
{
    /**
     * Valid data provider for marshalling
     *
     * @return array
     */
    public function validDataProviderForMarshalling()
    {
        return [
            [null, null],
            [BusctlDataObject::s(), 's '],
            [BusctlDataObject::s('hello world'), 's "hello world"'],
            [
                BusctlDataObject::r([
                    BusctlDataObject::s('string'),
                    BusctlDataObject::y(123)
                ]),
                '(sy) "string" 123'
            ],
            [
                BusctlDataObject::a([
                    BusctlDataObject::y(1),
                    BusctlDataObject::y(2),
                    BusctlDataObject::y(3),
                ]),
                'ay 3 1 2 3'
            ],
            [
                BusctlDataObject::v(BusctlDataObject::s('variant')),
                'v s "variant"'
            ],
            [
                BusctlDataObject::e([
                    [
                        'key' => BusctlDataObject::s('key'),
                        'value' => BusctlDataObject::v(BusctlDataObject::y(123))
                    ]
                ]),
                'a{sv} 1 "key" y 123'
            ],
            [
                BusctlDataObject::e([
                    [
                        'key' => BusctlDataObject::s('key'),
                        'value' => BusctlDataObject::e([
                            [
                                'key' => BusctlDataObject::s('item'),
                                'value' => BusctlDataObject::v(
                                    BusctlDataObject::u(123)
                                )
                            ]
                        ])
                    ]
                ]),
                'a{sa{sv}} 1 "key" 1 "item" u 123'
            ],
            [
                [
                    BusctlDataObject::s('hello'),
                    BusctlDataObject::s('world'),
                ],
                'ss "hello" "world"'
            ]
        ];
    }

    /**
     * Valid data provider for unmarshalling
     *
     * @return array
     */
    public function validDataProviderForUnmarshalling()
    {
        return [
            ['e', [], null],
            ['s', ['"hello"'], 'hello'],
            ['su', ['"hello"', 123], ['hello', 123]],
            ['ay', [3, 1, 2, 3], [1, 2, 3]],
            [
                'a{sa{sv}}',
                [1, '"key"', 1, '"item"', 'y', 123],
                ['key' => ['item' => 123]]
            ],
        ];
    }

    /**
     * Invalid data provider for marshalling
     *
     * @return array
     */
    public function invalidDataProviderForMarshalling()
    {
        return [
            [123],
            ['string'],
            [true],
            [[]],
            [[123]],
            [new stdClass()]
        ];
    }

    /**
     * Invalid data provider for unmarshalling
     *
     * @return array
     */
    public function invalidDataProviderForUnmarshalling()
    {
        return [
            [null, []],
            [123, []],
            [true, []],
            [[], []],
            [new stdClass(), []],
            ['s', null],
            ['s', 123],
            ['s', true],
            ['s', new stdClass()],
        ];
    }

    /**
     * Can be marshalled from valid
     *
     * @dataProvider validDataProviderForMarshalling
     *
     * @param mixed $data
     * @param mixed $expected
     * @return void
     */
    public function testCanBeMarshaledFromValidData($data, $expected)
    {
        $marshaller = new BusctlMarshaller();

        $this->assertInstanceOf(Marshaller::class, $marshaller);
        $this->assertEquals($expected, $marshaller->marshal($data));
    }

    /**
     * Cannot be marshalled from invalid data
     *
     * @dataProvider invalidDataProviderForMarshalling
     *
     * @param mixed $data
     * @return void
     */
    public function testCannotBeMarshalledFromInvalidData($data)
    {
        $this->expectException(InvalidArgumentException::class);

        $marshaller = new BusctlMarshaller();
        $marshaller->marshal($data);
    }

    /**
     * Can be unmarshalled from valid data
     *
     * @dataProvider validDataProviderForUnmarshalling
     *
     * @param mixed $signature
     * @param mixed $data
     * @param array $expected
     * @return void
     */
    public function testCanBeUnmarshalledFromValidData(
        $signature,
        $data,
        $expected
    ) {
        $marshaller = new BusctlMarshaller();

        $this->assertInstanceOf(Marshaller::class, $marshaller);
        $this->assertEquals($expected, $marshaller->unmarshal($signature, $data));
    }

    /**
     * Cannot be unmarshalled from invalid data
     *
     * @dataProvider invalidDataProviderForUnmarshalling
     *
     * @param mixed $signature
     * @param mixed $data
     * @return void
     */
    public function testCannotBeUnmarshalledFromInvalidData($signature, $data)
    {
        $this->expectException(InvalidArgumentException::class);

        $marshaller = new BusctlMarshaller();
        $marshaller->unmarshal($signature, $data);
    }
}