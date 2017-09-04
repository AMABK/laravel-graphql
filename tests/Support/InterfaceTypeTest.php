<?php

use Folklore\GraphQL\Support\InterfaceType;
use GraphQL\Type\Definition\InterfaceType as BaseInterfaceType;
use GraphQL\Type\Definition\Type;

/**
 * @coversDefaultClass \Folklore\GraphQL\Support\InterfaceType
 */
class InterfaceTypeTest extends TestCase
{
    protected $type;

    public function setUp()
    {
        parent::setUp();

        $this->type = app(InterfaceType::class);
    }

    /**
     * Test get and set type resolver
     *
     * @test
     * @covers ::setTypeResolver
     * @covers ::getTypeResolver
     */
    public function testGetTypeResolver()
    {
        $this->assertNull($this->type->getTypeResolver());
        $typeResolver = function ($root) {
            return Type::string();
        };
        $this->type->setTypeResolver($typeResolver);
        $this->assertEquals($typeResolver, $this->type->getTypeResolver());
    }

    /**
     * Test type resolver from resolveType method
     *
     * @test
     * @covers ::getTypeResolver
     */
    public function testGetTypeResolverMethod()
    {
        $type = new ExampleInterfaceType();
        $typeResolver = $type->getTypeResolver();
        $this->assertInstanceOf(Closure::class, $typeResolver);
        $this->assertEquals(Type::string(), $typeResolver());

        $interfaceMock = $this->getMockBuilder(ExampleInterfaceType::class)
            ->setMethods(['resolveType'])
            ->getMock();
        $interfaceMock->expects($this->once())
            ->method('resolveType')
            ->willReturn(Type::string());
        $typeResolver = $interfaceMock->getTypeResolver();
        $this->assertEquals(Type::string(), $typeResolver());
    }

    /**
     * Test that resolveType is in toArray
     *
     * @test
     * @covers ::toArray
     */
    public function testToArrayResolveType()
    {
        $typeResolver = function ($root) {
            return Type::string();
        };
        $this->type->setTypeResolver($typeResolver);
        $array = $this->type->toArray();
        $this->assertArrayHasKey('resolveType', $array);
        $this->assertEquals($typeResolver, $array['resolveType']);
    }

    /**
     * Test to type
     *
     * @test
     * @covers ::toType
     */
    public function testToType()
    {
        $this->type->name = 'ExampleInterfaceType';
        $objectType = $this->type->toType();
        $this->assertInstanceOf(BaseInterfaceType::class, $objectType);
    }
}


class ExampleInterfaceType extends InterfaceType
{
    protected function attributes()
    {
        return [
            'name' => 'name',
            'description' => 'description'
        ];
    }

    protected function fields()
    {
        return [
            'test' => [
                'type' => Type::string()
            ]
        ];
    }

    public function resolveType()
    {
        return Type::string();
    }
}
