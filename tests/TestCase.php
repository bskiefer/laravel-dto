<?php

declare(strict_types=1);

namespace bkief29\DTO\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function markTestSucceeded()
    {
        $this->assertTrue(true);
    }

    protected function getPackageProviders($app)
    {
        return ['bkief29\DTO\DataTransferObjectServiceProvider'];
    }
    protected function getPackageAliases($app)
    {
        return [
            'DTO' => 'bkief29\DTO\Facades\DTO'
        ];
    }
}
