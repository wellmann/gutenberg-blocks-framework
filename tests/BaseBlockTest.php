<?php

namespace KWIO\GutenbergBlocksFramework\Tests;

use KWIO\GutenbergBlocksFramework\BaseBlock;
use KWIO\GutenbergBlocksFramework\View\PhpView;
use KWIO\GutenbergBlocksFramework\PluginConfigDTO;
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
        $this->pluginConfig->viewClass = new PhpView();
    }

    public function testSetViewReturnsEmptyStringIfViewFileIsNull()
    {
        $block = new BaseBlock('example', 'src/', $this->pluginConfig);
        $blockReflection = new ReflectionClass($block);

        $blockReflectionSetView = $blockReflection->getMethod('setView');
        $blockReflectionSetView->setAccessible(true);
        $result = $blockReflectionSetView->invokeArgs($block, [null]);

        $this->assertSame('', $result);
    }

    public function testSetViewReturnsEmptyStringIfMobileAndHideMobile()
    {
        when('wp_is_mobile')->justReturn(true);

        $block = new BaseBlock('example', 'src/', $this->pluginConfig);
        $blockReflection = new ReflectionClass($block);

        $blockReflectionHideMobile = $blockReflection->getProperty('hideMobile');
        $blockReflectionHideMobile->setAccessible(true);
        $blockReflectionHideMobile->setValue($block, true);

        $blockReflectionSetView = $blockReflection->getMethod('setView');
        $blockReflectionSetView->setAccessible(true);

        $this->assertSame('', $blockReflectionSetView->invokeArgs($block, ['']));
    }

    public function testSetViewReturnsEmptyStringIfNotMobileAndHideDesktop()
    {
        $block = new BaseBlock('example', 'src/', $this->pluginConfig);
        $blockReflection = new ReflectionClass($block);

        $blockReflectionHideDesktop = $blockReflection->getProperty('hideDesktop');
        $blockReflectionHideDesktop->setAccessible(true);
        $blockReflectionHideDesktop->setValue($block, true);

        $blockReflectionSetView = $blockReflection->getMethod('setView');
        $blockReflectionSetView->setAccessible(true);

        $this->assertSame('', $blockReflectionSetView->invokeArgs($block, ['']));
    }

    public function testSetViewReturnsEmptyWrapperDivIfViewFileIsEmpty()
    {
        $block = new BaseBlock('example', 'src/', $this->pluginConfig);
        $blockReflection = new ReflectionClass($block);
        $block->render([], '');

        $blockReflectionSetView = $blockReflection->getMethod('setView');
        $blockReflectionSetView->setAccessible(true);
        $result = $blockReflectionSetView->invokeArgs($block, ['']);

        $this->assertSame('<div class="block block-example"></div>', $result);
    }

    public function testSetViewReturnsWrapperDivWithContentIfViewFileIsEmpty()
    {
        $block = new BaseBlock('example', 'src/', $this->pluginConfig);
        $blockReflection = new ReflectionClass($block);
        $block->render([], 'lorem ipsum');

        $blockReflectionSetView = $blockReflection->getMethod('setView');
        $blockReflectionSetView->setAccessible(true);
        $result = $blockReflectionSetView->invokeArgs($block, ['']);

        $this->assertSame('<div class="block block-example">lorem ipsum</div>', $result);
    }

    public function testSetViewReturnsCoreBlockWithoutWrapperDivtIfViewFileIsEmpty()
    {
        $block = new BaseBlock('core-example', 'src/', $this->pluginConfig);
        $blockReflection = new ReflectionClass($block);
        $block->render([], 'lorem ipsum');

        $blockReflectionSetView = $blockReflection->getMethod('setView');
        $blockReflectionSetView->setAccessible(true);
        $result = $blockReflectionSetView->invokeArgs($block, ['']);

        $this->assertSame('lorem ipsum', $result);
    }

    public function testSetViewReturnsWrapperDivWithContent()
    {
        $block = new BaseBlock('example', 'src/', $this->pluginConfig);
        $result = $block->render([], 'lorem ipsum');

        $this->assertSame('<div class="block block-example">lorem ipsum</div>', $result);
    }

    public function testSetViewReturnsCoreBlockWithoutWrapperDiv()
    {
        $block = new BaseBlock('core-example', 'src/', $this->pluginConfig);
        $result = $block->render([], 'lorem ipsum');

        $this->assertSame('lorem ipsum', $result);
    }

    public function testTagAttrHasBlockClassNames()
    {
        $block = new BaseBlock('example', 'src/', $this->pluginConfig);
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
        $block = new BaseBlock('example', 'src/', $this->pluginConfig);
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
        $block = new BaseBlock('example', 'src/', $this->pluginConfig);
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
        $block = new BaseBlock('example', 'src/', $this->pluginConfig);
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
        $block = new BaseBlock('example', 'src/', $this->pluginConfig);
        $renderResult = $block->render([
            'align' => 'full',
            'className' => 'additional-class'
        ], '');

        $this->assertTrue(strpos($renderResult, 'class="block block-example additional-class alignfull"') !== false);
    }

    public function testRenderResultHasHtmlIdAttribute()
    {
        $block = new BaseBlock('example', 'src/', $this->pluginConfig);
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

    public function testConvertIsStyleToBem()
    {
        $block = new BaseBlock('example', 'src/', $this->pluginConfig);
        $blockReflection = new ReflectionClass($block);

        $blockReflectionConvertIsStyleToBem = $blockReflection->getMethod('convertIsStyleToBem');
        $blockReflectionConvertIsStyleToBem->setAccessible(true);
        $result = $blockReflectionConvertIsStyleToBem->invokeArgs($block, [['block', 'block-example', 'is-style-modifier']]);

        $this->assertArrayHasKey('block-example--modifier', array_flip($result));
    }

    public function testBuildTagAttrString()
    {
        when('esc_attr')->returnArg();

        $block = new BaseBlock('example', 'src/', $this->pluginConfig);
        $blockReflection = new ReflectionClass($block);

        $blockReflectionBuildTagAttrString = $blockReflection->getMethod('buildTagAttrString');
        $blockReflectionBuildTagAttrString->setAccessible(true);
        $result = $blockReflectionBuildTagAttrString->invokeArgs($block, [[
            'class' => ['block', 'block-example'],
            'id' => ['anchor']
        ]]);

        $this->assertEquals(' class="block block-example" id="anchor"', $result);
    }
}