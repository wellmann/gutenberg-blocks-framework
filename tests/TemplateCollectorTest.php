<?php

namespace KWIO\GutenbergBlocksFramework\Tests;

use KWIO\GutenbergBlocksFramework\PluginConfigDTO;
use ReflectionClass;
use KWIO\GutenbergBlocksFramework\TemplateCollector;

class TemplateCollectorTest extends TestCase
{
    public function testAddNamespaceToBlockName()
    {
        $this->pluginConfig = new PluginConfigDTO();
        $this->pluginConfig->prefix = 'prefix';

        $templateCollector = new TemplateCollector($this->pluginConfig);
        $templateCollectorReflection = new ReflectionClass($templateCollector);

        $templateCollectorAddNamespace = $templateCollectorReflection->getMethod('addNamespaceToBlockName');
        $templateCollectorAddNamespace->setAccessible(true);
        $result = $templateCollectorAddNamespace->invokeArgs($templateCollector, [[
            ['core/image'],
            ['example-block'],
        ]]);

        $this->assertEquals([
            ['core/image'],
            ['prefix/example-block'],
        ], $result);
    }
}