<?php

declare(strict_types=1);

namespace KWIO\GutenbergBlocks\Tests;

use KWIO\GutenbergBlocks\Config;
use ReflectionClass;
use KWIO\GutenbergBlocks\TemplateCollector;

class TemplateCollectorTest extends TestCase
{
    public function testAddNamespaceToBlockName()
    {
        $this->config = new Config();
        $this->config->namespace = 'namespace';

        $templateCollector = new TemplateCollector($this->config);
        $templateCollectorReflection = new ReflectionClass($templateCollector);

        $templateCollectorAddNamespace = $templateCollectorReflection->getMethod('addNamespaceToBlockName');
        $templateCollectorAddNamespace->setAccessible(true);
        $result = $templateCollectorAddNamespace->invokeArgs($templateCollector, [[
            ['core/image'],
            ['example-block'],
        ]]);

        $this->assertEquals([
            ['core/image'],
            ['namespace/example-block'],
        ], $result);
    }
}