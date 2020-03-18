<?php

namespace bkief29\DTO;

use bkief29\DTO\DataTransferObjectError;
use Spatie\DataTransferObject\DataTransferObject as BaseDTO;
use ReflectionClass;

abstract class DataTransferObject extends BaseDTO
{
    use Caster {
        Caster::__construct as private __castConstruct;
    }

    public function __construct(array $parameters = [])
    {
        $this->__castConstruct($parameters);

        $parameters = $this->attributesToArray();

        $validators = $this->getFieldValidators();

        $valueCaster = $this->getValueCaster();

        foreach ($validators as $field => $validator) {
            if (
                ! isset($parameters[$field])
                && ! $validator->hasDefaultValue
                && ! $validator->isNullable
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

            if (! $validator->isValidType($value)) {
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

        if (! $this->ignoreMissing && count($parameters)) {
            throw DataTransferObjectError::unknownProperties(array_keys($parameters), static::class);
        }
    }
}