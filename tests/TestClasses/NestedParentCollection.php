<?php

namespace bkief29\DTO\Tests\TestClasses;

use bkief29\DTO\DataTransferObjectCollection;

class NestedParentCollection extends DataTransferObjectCollection
{
    public function current(): NestedChildCollection
    {
        return parent::current();
    }
}
