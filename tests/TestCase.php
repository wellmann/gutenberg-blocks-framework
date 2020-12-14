<?php

namespace KWIO\GutenbergBlocksFramework\Tests;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Brain\Monkey;

class TestCase extends \PHPUnit\Framework\TestCase
{
    use MockeryPHPUnitIntegration;

    protected function setUp(): void
    {
        parent::setUp();
        Monkey\setUp();
    }

    protected function tearDown(): void
    {
        Monkey\tearDown();
        parent::tearDown();
    }
}