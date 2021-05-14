<?php

namespace bkief29\DTO;

use ArrayAccess;
use Countable;
use Illuminate\Support\Collection;
use Iterator;
use Spatie\DataTransferObject\DataTransferObjectError;
use Spatie\DataTransferObject\DTOCache;
use Spatie\DataTransferObject\FieldValidator;
use ReflectionClass;
use ReflectionProperty;
use Spatie\DataTransferObject\ValueCaster;

abstract class DataTransferObjectCollection implements
    ArrayAccess,
    Iterator,
    Countable
{
    /** @var int */
    protected $position = 0;

    public $collection;

    public function __construct(array $collection = [])
    {
        $this->collection = new Collection($collection);
        if (0 === $this->collection->count()) {
            return;
        }

        $validators = $this->getFieldValidators();

        $valueCaster = $this->getValueCaster();

        foreach ($validators as $field => $validator) {
            if (
                !isset($parameters[$field])
                && !$validator->hasDefaultValue
                && !$validator->isNullable
            ) {
                throw DataTransferObjectError::uninitialized(
                    static::class,
                    $field
                );
            }

            $value = $collection;

            $value = $this->castValue($valueCaster, $validator, $collection);
            $this->{$field} = new Collection($value);
        }
    }

    public function __call($name, $arguments)
    {
        return $this->collection->{$name}(...$arguments);
    }

    public function toArray(): array
    {
        $collection = $this->collection->toArray();

        foreach ($collection as $key => $item) {
            if (!$item instanceof DataTransferObject
                && !$item instanceof self
            ) {
                continue;
            }

            $collection[$key] = $item->toArray();
        }

        return $collection;
    }

    public function current()
    {
        return $this->collection->get($this->position);
    }

    public function offsetGet($offset)
    {
        return $this->collection->get($offset) ?? null;
    }

    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            $this->collection->push($value);
        } else {
            $this->collection->put($offset, $value);
        }
    }

    public function offsetExists($offset): bool
    {
        return $this->collection->has($offset);
    }

    public function offsetUnset($offset)
    {
        $this->collection->forget($offset);
    }

    public function next()
    {
        ++$this->position;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return $this->collection->has($this->position);
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function count(): int
    {
        return $this->collection->count();
    }

    /**
     * @param \ReflectionClass $class
     * @return \Spatie\DataTransferObject\FieldValidator[]
     */
    protected function getFieldValidators(): array
    {
        return DTOCache::resolve(
            static::class,
            function () {
                $class = new ReflectionClass(static::class);

                $properties = [];

                foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $reflectionProperty) {
                    // Skip static properties
                    if ($reflectionProperty->isStatic()) {
                        continue;
                    }

                    $field = $reflectionProperty->getName();

                    $properties[$field] = FieldValidator::fromReflection($reflectionProperty);
                }

                return $properties;
            }
        );
    }

    /**
     * @param \Spatie\DataTransferObject\ValueCaster $valueCaster
     * @param \Spatie\DataTransferObject\FieldValidator $fieldValidator
     * @param mixed $value
     * @return mixed
     */
    protected function castValue(ValueCaster $valueCaster, FieldValidator $fieldValidator, $value)
    {
        if ($value instanceof Collection) {
            return $valueCaster->cast($value->toArray(), $fieldValidator);
        }
        if (is_array($value)) {
            return $valueCaster->cast($value, $fieldValidator);
        }

        return $value;
    }


    protected function getValueCaster(): ValueCaster
    {
        return new ValueCaster();
    }
}
