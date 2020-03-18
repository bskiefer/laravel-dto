<?php

namespace bkief29\DTO\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class DTO.
 *
 * @method static \bkief29\DTO\DataTransferObject init(array $attributes)
 * @mixin \bkief29\DTO\DataTransferObject
 */
class DTO extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'dto';
    }
}
