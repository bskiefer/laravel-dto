<?php

namespace bkief29\DTO;

use Spatie\DataTransferObject\DataTransferObject as BaseDTO;
use Spatie\DataTransferObject\DataTransferObjectCollection;

abstract class DataTransferObject extends BaseDTO
{
    use Caster {
        Caster::__construct as private __castConstruct;
    }

    public function __construct(array $parameters = [])
    {
        $this->init($parameters);
    }

    public function init(array $parameters)
    {
        $this->__castConstruct($parameters);

        $parameters = $this->attributesToArray();

        $validators = $this->getFieldValidators();

        $valueCaster = $this->getValueCaster();

        foreach ($validators as $field => $validator) {
            if (!isset($parameters[$field])
                && !$validator->hasDefaultValue
                && !$validator->isNullable
            ) {
                throw DataTransferObjectError::uninitialized(
                    static::class,
                    $field
                );
            }

            $value = $parameters[$field] ?? $this->{$field} ?? null;

            $value = $this->castValue($valueCaster, $validator, $value);

            if ($this->hasGetMutator($field) || $this->hasCast($field)) {
                $value = $this->getAttributeValue($field);
            }

            if (!$validator->isValidType($value)) {
                throw DataTransferObjectError::invalidType(
                    static::class,
                    $field,
                    $validator->allowedTypes,
                    $value
                );
            }

            $this->{$field} = $value;

            unset($parameters[$field]);
        }

        if (!$this->ignoreMissing && count($parameters)) {
            throw DataTransferObjectError::unknownProperties(array_keys($parameters), static::class);
        }
    }


    protected function parseArray(array $array): array
    {
        foreach ($array as $key => $value) {
            if (
                $value instanceof \Spatie\DataTransferObject\DataTransferObject
                || $value instanceof \Spatie\DataTransferObject\DataTransferObjectCollection
                || $value instanceof \bkief29\DTO\DataTransferObject
                || $value instanceof \bkief29\DTO\DataTransferObjectCollection
            ) {
                $array[$key] = $value->toArray();

                continue;
            }

            if (!is_array($value)) {
                continue;
            }

            $array[$key] = $this->parseArray($value);
        }

        return $array;
    }
}
