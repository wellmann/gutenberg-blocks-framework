<?php

declare(strict_types=1);

namespace KWIO\GutenbergBlocks\Tests;

use KWIO\GutenbergBlocks\BlockCollector;
use KWIO\GutenbergBlocks\View\PhpView;
use KWIO\GutenbergBlocks\Config;
use ReflectionClass;

use function Brain\Monkey\Functions\when;

class BlockCollectorTest extends TestCase
{
    protected ?Config $config = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->config = new Config();
        $this->config->blockDir = 'src/';
        $this->config->dirPath = '/';
    }

    public function testRegisterBlock()
    {
        when('wp_json_file_decode')->justReturn([]);
        when('register_block_type')->justReturn(true);

        $this->config->prefix = 'prefix';
        $this->config->namespace = 'Namespace';
        $this->config->viewClass = PhpView::class;

        $blockCollector = new BlockCollector($this->config);
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

        $this->config->prefix = 'prefix';
        $this->config->namespace = 'Namespace';
        $this->config->viewClass = PhpView::class;

        $blockCollector = new BlockCollector($this->config);
        $blockCollectorReflection = new ReflectionClass($blockCollector);

        $blockCollectorRegisterBlock = $blockCollectorReflection->getMethod('registerBlock');
        $blockCollectorRegisterBlock->setAccessible(true);
        $blockCollectorRegisterBlock->invokeArgs($blockCollector, ['core-image']);

        $this->assertNotFalse(has_filter('render_block'));
    }
}