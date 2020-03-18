<?php

declare(strict_types=1);

namespace bkief29\DTO\Tests;

use ArrayIterator;
use bkief29\DTO\DataTransferObject;
use Carbon\Carbon;
use Spatie\DataTransferObject\DataTransferObjectError;
use bkief29\DTO\Tests\TestClasses\DummyClass;
use bkief29\DTO\Tests\TestClasses\EmptyChild;
use bkief29\DTO\Tests\TestClasses\NestedChild;
use bkief29\DTO\Tests\TestClasses\NestedParent;
use bkief29\DTO\Tests\TestClasses\NestedParentOfMany;
use bkief29\DTO\Tests\TestClasses\OtherClass;
use bkief29\DTO\Tests\TestClasses\TestDataTransferObject;

class DataTransferObjectCastTest extends TestCase
{
    /** @test */
    public function cast_attribute_using_getter()
    {
        $valueObject = new class(['foo' => '1234']) extends DataTransferObject {
            /** @var int */
            public $foo;

            public function getFooAttribute() {
                return (int) $this->getOriginal('foo');
            }
        };

        $this->assertEquals(['foo' => 1234], $valueObject->only('foo')->toArray());

        $this->markTestSucceeded();
    }

    /** @test */
    public function cast_attribute_using_casts_property()
    {
        $valueObject = new class(['foo' => '1234']) extends DataTransferObject {
            /** @var int */
            public $foo;

            protected $casts = [
                'foo' => 'int'
            ];
        };

        $this->assertEquals(['foo' => 1234], $valueObject->only('foo')->toArray());

        $this->markTestSucceeded();
    }

    /** @test */
    public function cast_attribute_using_getting_with_value_argument()
    {
        $valueObject = new class(['foo' => '1234']) extends DataTransferObject {
            /** @var int */
            public $foo;

            public function getFooAttribute($value) {
                return (int) $value;
            }
        };

        $this->assertEquals(['foo' => 1234], $valueObject->only('foo')->toArray());

        $this->markTestSucceeded();
    }

    /** @test */
    public function cast_attribute_using_getter_access_methods()
    {
        $valueObject = new class(['foo' => '1234']) extends DataTransferObject {
            /** @var int */
            public $foo;

            public function getFooAttribute() {
                return (int) $this->getOriginal('foo');
            }
        };

        $this->assertEquals(['foo' => $valueObject->foo], $valueObject->only('foo')->toArray());
        $this->assertEquals(['foo' => $valueObject->getAttribute('foo')], $valueObject->only('foo')->toArray());

        $this->markTestSucceeded();
    }

    /** @test */
    public function cast_attribute_using_date_property()
    {
        $valueObject = new class(['date' => '12/12/1999']) extends DataTransferObject {
            /** @var \Carbon\Carbon|string */
            public $date;

            protected $dates = [
                'date'
            ];
        };

        $d = \DateTime::createFromFormat('Y-m-d H:i:s', $valueObject->date);
        $this->assertTrue($d && $d->format('Y-m-d H:i:s') == $valueObject->date);

        $this->markTestSucceeded();
    }

    /** @test */
    public function cast_attribute_using_date_cast()
    {
        $valueObject = new class(['dateVar' => '1999/12/12']) extends DataTransferObject {
            /** @var \Carbon\Carbon */
            public $dateVar;

            protected $dateFormat = 'YYYY/MM/DD';

            protected $casts = [
                'dateVar' => 'date'
            ];
        };;

        $this->assertInstanceOf(\Carbon\Carbon::class, $valueObject->dateVar);

        $this->markTestSucceeded();
    }
}
