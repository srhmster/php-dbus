<?php

declare(strict_types=1);

namespace Srhmster\PhpDbus\Tests;

use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;
use Srhmster\PhpDbus\DataObjects\BusctlDataObject;
use Srhmster\PhpDbus\Marshallers\{BusctlMarshaller, Marshaller};
use Srhmster\PhpDbus\Tests\DataProviders\MarshallerDataProvider;
use TypeError;

/**
 * BusctlMarshaller class tests
 */
final class BusctlMarshallerTest extends TestCase
{
    /**
     * Can be marshalled from valid
     *
     * @see MarshallerDataProvider::validMarshallingData()
     *
     * @param BusctlDataObject|BusctlDataObject[]|null $data
     * @param string|null $expected
     * @return void
     */
    #[DataProviderExternal(MarshallerDataProvider::class, 'validMarshallingData')]
    public function testCanBeMarshaledFromValidData(
        BusctlDataObject|array|null $data,
        ?string $expected
    ): void
    {
        $marshaller = new BusctlMarshaller();

        $this->assertInstanceOf(Marshaller::class, $marshaller);
        $this->assertEquals($expected, $marshaller->marshal($data));
    }

    /**
     * Cannot be marshalled from invalid data
     *
     * @see MarshallerDataProvider::invalidMarshallingData()
     *
     * @param mixed $data
     * @return void
     */
    #[DataProviderExternal(MarshallerDataProvider::class, 'invalidMarshallingData')]
    public function testCannotBeMarshalledFromInvalidData(mixed $data): void
    {
        $this->expectException(TypeError::class);

        $marshaller = new BusctlMarshaller();
        $marshaller->marshal($data);
    }

    /**
     * Can be unmarshalled from valid data
     *
     * @see MarshallerDataProvider::validUnmarshallingData()
     *
     * @param string $data
     * @param mixed $expected
     * @return void
     */
    #[DataProviderExternal(MarshallerDataProvider::class, 'validUnmarshallingData')]
    public function testCanBeUnmarshalledFromValidData(
        string $data,
        mixed $expected
    ): void
    {
        $marshaller = new BusctlMarshaller();

        $this->assertInstanceOf(Marshaller::class, $marshaller);
        $this->assertEquals($expected, $marshaller->unmarshal($data));
    }

    /**
     * Cannot be unmarshalled from invalid data
     *
     * @see MarshallerDataProvider::invalidUnmarshallingData()
     *
     * @param mixed $data
     * @return void
     */
    #[DataProviderExternal(MarshallerDataProvider::class, 'invalidUnmarshallingData')]
    public function testCannotBeUnmarshalledFromInvalidData(
        mixed $data
    ): void
    {
        $this->expectException(TypeError::class);
        
        $marshaller = new BusctlMarshaller();
        $marshaller->unmarshal($data);
    }
}