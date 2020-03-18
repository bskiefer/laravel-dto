<?php

declare(strict_types=1);

namespace bkief29\DTO\Tests\TestClasses;

use bkief29\DTO\DataTransferObject;

class NullableTestDataTransferObject extends DataTransferObject
{
    /** @var string */
    public $foo = 'abc';

    /** @var bool|null */
    public $bar;
}
