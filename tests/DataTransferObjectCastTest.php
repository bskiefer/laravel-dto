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
    public function cast_attribute_using_setter()
    {
        $valueObject = new class(['foo' => '1234']) extends DataTransferObject {
            /** @var int */
            public $foo;

            public function getFooAttribute() {
                return (int) 4321;
            }
        };

        $this->assertEquals(['foo' => $valueObject->foo], $valueObject->only('foo')->toArray());
        $this->assertEquals(['foo' => $valueObject->getAttribute('foo')], $valueObject->only('foo')->toArray());

        $valueObject->setAttribute('foo', '0000');

        $this->assertEquals('0000', $valueObject->foo);
        $this->assertNotEquals('0000', $valueObject->getAttribute('foo'));

        $this->markTestSucceeded();
    }

    /** @test */
    public function cast_attribute_using_setter_methods()
    {
        $valueObject = new class(['foo' => '1234', 'bar' => '4567', 'xyz' => null, 'abc' => '']) extends DataTransferObject {
            /** @var int */
            public $foo;
            /** @var int */
            public $bar;
            /** @var string|null */
            public $xyz;
            /** @var string|null */
            public $abc;

            protected $casts = [
                'foo' => 'int',
                'bar' => 'int',
                'xyz' => 'string',
            ];

            public function setFooAttribute(string $value) {
                return $value;
            }

            public function setBarAttribute($value) {
                return $value;
            }

            public function setXyzAttribute($value) {
                return $value ?? 123;
            }

            public function setAbcAttribute($value) {
                return '123';
            }
        };

        $this->assertEquals(gettype($valueObject->foo), 'integer');
        $this->assertEquals(gettype($valueObject->bar), 'integer');
        $this->assertEquals(gettype($valueObject->xyz), 'string');
        $this->assertEquals(gettype($valueObject->abc), 'string');

        $this->assertEquals($valueObject->abc, '123');
        $this->assertEquals($valueObject->xyz, '123');

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
