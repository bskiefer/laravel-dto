<?php

namespace bkief29\DTO\Tests\TestClasses;

use bkief29\DTO\DataTransferObjectCollection;

class NestedChildCollection extends DataTransferObjectCollection
{
    public function current(): NestedParent
    {
        return parent::current();
    }
}
