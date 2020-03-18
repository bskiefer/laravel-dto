<?php

declare(strict_types=1);

namespace bkief29\DTO\Tests\TestClasses;

use bkief29\DTO\DataTransferObject;

class NestedParentOfMany extends DataTransferObject
{
    /** @var \bkief29\DTO\Tests\TestClasses\NestedChild[] */
    public $children;

    /** @var string */
    public $name;
}
