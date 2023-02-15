<?php

declare(strict_types=1);

namespace KWIO\GutenbergBlocks\Tests;

use KWIO\GutenbergBlocks\BaseBlock;
use KWIO\GutenbergBlocks\View\PhpView;
use KWIO\GutenbergBlocks\PluginConfigDTO;
use ReflectionClass;

use function Brain\Monkey\Functions\when;

class BaseBlockTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        when('wp_is_mobile')->justReturn(false);
        when('esc_attr')->returnArg();

        $this->pluginConfig = new PluginConfigDTO();
        $this->pluginConfig->viewClass = PhpView::class;
    }

    public function testTagAttrHasBlockClassNames()
    {
        $block = new BaseBlock('example', __DIR__ . '/data/', $this->pluginConfig);
        $blockReflection = new ReflectionClass($block);
        $blockReflectionTagAttr = $blockReflection->getProperty('tagAttr');
        $blockReflectionTagAttr->setAccessible(true);

        $block->render([], '');

        $this->assertEquals([
            'class' => ['block', 'block-example']
        ], $blockReflectionTagAttr->getValue($block));
    }

    public function testTagAttrHasAdditionalClassName()
    {
        $block = new BaseBlock('example', __DIR__ . '/data/', $this->pluginConfig);
        $blockReflection = new ReflectionClass($block);
        $blockReflectionTagAttr = $blockReflection->getProperty('tagAttr');
        $blockReflectionTagAttr->setAccessible(true);

        $block->render(['className' => 'additional-class'], '');

        $this->assertEquals([
            'class' => ['block', 'block-example', 'additional-class']
        ], $blockReflectionTagAttr->getValue($block));
    }

    public function testTagAttrHasAlign()
    {
        $block = new BaseBlock('example', __DIR__ . '/data/', $this->pluginConfig);
        $blockReflection = new ReflectionClass($block);
        $blockReflectionTagAttr = $blockReflection->getProperty('tagAttr');
        $blockReflectionTagAttr->setAccessible(true);

        $block->render(['align' => 'full'], '');

        $this->assertEquals([
            'class' => ['block', 'block-example', 'alignfull']
        ], $blockReflectionTagAttr->getValue($block));
    }

    public function testTagAttrHasAnchor()
    {
        $block = new BaseBlock('example', __DIR__ . '/data/', $this->pluginConfig);
        $blockReflection = new ReflectionClass($block);
        $blockReflectionTagAttr = $blockReflection->getProperty('tagAttr');
        $blockReflectionTagAttr->setAccessible(true);

        $block->render(['anchor' => 'anchor'], '');

        $this->assertEquals([
            'class' => ['block', 'block-example'],
            'id' => ['anchor']
        ], $blockReflectionTagAttr->getValue($block));
    }

    public function testRenderResultHasClassNames()
    {
        $block = new BaseBlock('example', __DIR__ . '/data/', $this->pluginConfig);
        $renderResult = $block->render([
            'align' => 'full',
            'className' => 'additional-class'
        ], '');

        $this->assertTrue(strpos($renderResult, 'class="block block-example additional-class alignfull"') !== false);
    }

    public function testRenderResultHasHtmlIdAttribute()
    {
        $block = new BaseBlock('example', __DIR__ . '/data/', $this->pluginConfig);
        $renderResult = $block->render(['anchor' => 'anchor'], '');

        $this->assertTrue(strpos($renderResult, 'id="anchor"') !== false);
    }

    public function testExtractAttr()
    {
        $block = new BaseBlock('example', 'src/', $this->pluginConfig);
        $blockReflection = new ReflectionClass($block);

        $blockReflectionData = $blockReflection->getProperty('data');
        $blockReflectionData->setAccessible(true);
        $blockReflectionData->setValue($block, ['className' => 'additional-class']);

        $blockReflectionExtractAttr = $blockReflection->getMethod('extractAttr');
        $blockReflectionExtractAttr->setAccessible(true);
        $result = $blockReflectionExtractAttr->invokeArgs($block, ['className', 'class']);

        $blockReflectionTagAttr = $blockReflection->getProperty('tagAttr');
        $blockReflectionTagAttr->setAccessible(true);

        $this->assertArrayHasKey('class', $blockReflectionTagAttr->getValue($block));
        $this->assertArrayNotHasKey('className', $blockReflectionData->getValue($block));
        $this->assertEquals('additional-class', $result);
    }

    public function testToSlug()
    {
        $this->assertEquals('base-block', BaseBlock::toSlug());
    }
}