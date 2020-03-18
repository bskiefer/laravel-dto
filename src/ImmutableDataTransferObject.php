<?php

namespace bkief29\DTO;

class ImmutableDataTransferObject
{
//    /** @var \Spatie\DataTransferObject\DataTransferObject */
    /**
     * @var \bkief29\DTO\DataTransferObject
     */
    protected $dataTransferObject;

    public function __construct(DataTransferObject $dataTransferObject)
    {
        $this->dataTransferObject = $dataTransferObject;
    }

    public function __set($name, $value)
    {
        throw DataTransferObjectError::immutable($name);
    }

    public function __get($name)
    {
        return $this->dataTransferObject->{$name};
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->dataTransferObject, $name], $arguments);
    }
}
