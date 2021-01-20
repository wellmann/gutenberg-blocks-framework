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

    /**
     * @dataProvider dataProviderForTestValidGroupTitle
     */
    public function testValidGroupTitle(string $prefix, string $title)
    {
        $this->pluginConfig->prefix = $prefix;

        $blockCollector = new BlockCollector($this->pluginConfig);
        $blockCategories = $blockCollector->groupBlocks([]);

        $this->assertEquals($title, $blockCategories[0]['title']);
    }

    public function testRegisterBlock()
    {
        when('register_block_type')->justReturn(true);

        $this->pluginConfig->prefix = 'prefix';
        $this->pluginConfig->namespace = 'Namespace';
        $this->pluginConfig->viewClass = new PhpView();

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
        $this->pluginConfig->viewClass = new PhpView();

        $blockCollector = new BlockCollector($this->pluginConfig);
        $blockCollectorReflection = new ReflectionClass($blockCollector);

        $blockCollectorRegisterBlock = $blockCollectorReflection->getMethod('registerBlock');
        $blockCollectorRegisterBlock->setAccessible(true);
        $blockCollectorRegisterBlock->invokeArgs($blockCollector, ['core-image']);

        $this->assertNotFalse(has_filter('render_block'));
    }

    public function dataProviderForTestValidGroupTitle()
    {
        return [
            ['kwio', 'Kwio'],
            ['my-prefix', 'My Prefix'],
            ['my-long-prefix', 'My Long Prefix'],
        ];
    }
}