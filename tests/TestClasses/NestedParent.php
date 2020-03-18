<?php

declare(strict_types=1);

namespace bkief29\DTO\Tests\TestClasses;

use bkief29\DTO\DataTransferObject;

class NestedParent extends DataTransferObject
{
    /** @var \bkief29\DTO\Tests\TestClasses\NestedChild */
    public $child;

    /** @var string */
    public $name;
}
