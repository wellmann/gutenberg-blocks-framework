<?php

namespace KWIO\GutenbergBlocksFramework\Tests;

use KWIO\GutenbergBlocksFramework\BlockCollector;
use KWIO\GutenbergBlocksFramework\View\PhpView;
use KWIO\GutenbergBlocksFramework\PluginConfigDTO;
use ReflectionClass;

use function Brain\Monkey\Functions\when;

class BlockCollectorTest extends TestCase
{
    protected ?PluginConfigDTO $pluginConfig = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pluginConfig = new PluginConfigDTO();
        $this->pluginConfig->blockDir = 'src/';
        $this->pluginConfig->dirPath = '/';
    }

    public function testRegisterBlock()
    {
        when('register_block_type')->justReturn(true);

        $this->pluginConfig->prefix = 'prefix';
        $this->pluginConfig->namespace = 'Namespace';
        $this->pluginConfig->viewClass = PhpView::class;

        $blockCollector = new BlockCollector($this->pluginConfig);
        $blockCollectorReflection = new ReflectionClass($blockCollector);

        $blockCollectorRegisterBlock = $blockCollectorReflection->getMethod('registerBlock');
        $blockCollectorRegisterBlock->setAccessible(true);
        $blockCollectorRegisterBlock->invokeArgs($blockCollector, ['example-block']);

        $blockCollectorBlocks = $blockCollectorReflection->getProperty('blocks');
        $blockCollectorBlocks->setAccessible(true);

        $this->assertContains('prefix/example-block', $blockCollectorBlocks->getValue($blockCollector));
    }

    public function testOverrideCoreBlock()
    {
        when('register_block_type')->justReturn(true);

        $this->pluginConfig->prefix = 'prefix';
        $this->pluginConfig->namespace = 'Namespace';
        $this->pluginConfig->viewClass = PhpView::class;

        $blockCollector = new BlockCollector($this->pluginConfig);
        $blockCollectorReflection = new ReflectionClass($blockCollector);

        $blockCollectorRegisterBlock = $blockCollectorReflection->getMethod('registerBlock');
        $blockCollectorRegisterBlock->setAccessible(true);
        $blockCollectorRegisterBlock->invokeArgs($blockCollector, ['core-image']);

        $this->assertNotFalse(has_filter('render_block'));
    }
}