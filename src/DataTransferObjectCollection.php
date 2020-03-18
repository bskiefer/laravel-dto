<?php

namespace bkief29\DTO;

use ArrayAccess;
use Countable;
use Illuminate\Support\Collection;
use Iterator;

abstract class DataTransferObjectCollection  implements
    ArrayAccess,
    Iterator,
    Countable
{
    /** @var int */
    protected $position = 0;

    /**
     * @var \Illuminate\Support\Collection
     */
    private $collection;

    public function __construct(array $collection = [])
    {
        $this->collection = new Collection($collection);
    }

    public function __call($name, $arguments)
    {
        return $this->collection->$name(... $arguments);
    }

    public function toArray(): array
    {
        $collection = $this->collection->toArray();

        foreach ($collection as $key => $item) {
            if (
                ! $item instanceof DataTransferObject
                && ! $item instanceof DataTransferObjectCollection
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
        if (is_null($offset)) {
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
        $this->position++;
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
        return$this->collection->count();
    }
}