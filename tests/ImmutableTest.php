<?php

namespace bkief29\DTO\Tests;

use bkief29\DTO\DataTransferObjectError;
use bkief29\DTO\Tests\TestClasses\NullableTestDataTransferObject;
use bkief29\DTO\Tests\TestClasses\TestDataTransferObject;

class ImmutableTest extends TestCase
{
    /** @test */
    public function immutable_values_cannot_be_overwritten()
    {
        $dto = TestDataTransferObject::immutable([
            'testProperty' => 1,
        ]);

        $this->assertEquals(1, $dto->testProperty);

        $this->expectException(\Spatie\DataTransferObject\DataTransferObjectError::class);
        $this->expectExceptionMessage('Cannot change the value of property testProperty on an immutable data transfer object');

        $dto->testProperty = 2;
    }

    /** @test */
    public function method_calls_are_proxied()
    {
        $dto = TestDataTransferObject::immutable([
            'testProperty' => 1,
        ]);

        $this->assertEquals(['testProperty' => 1], $dto->toArray());
    }

    /** @test */
    public function passing_parameters_is_not_required()
    {
        $dto = NullableTestDataTransferObject::immutable();

        $this->assertEquals(['foo' => 'abc', 'bar' => null], $dto->toArray());
    }
}
