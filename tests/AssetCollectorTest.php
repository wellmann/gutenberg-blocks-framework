<?php

namespace KWIO\GutenbergBlocksFramework\Tests;

use KWIO\GutenbergBlocksFramework\AssetCollector;
use KWIO\GutenbergBlocksFramework\PluginConfigDTO;
use ReflectionClass;

use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Functions\when;

class AssetCollectorTest extends TestCase
{
    protected ?PluginConfigDTO $pluginConfig = null;

    protected function setUp(): void
    {
        parent::setUp();

        when('is_admin')->justReturn(false);

        $this->pluginConfig = new PluginConfigDTO();
        $this->pluginConfig->dirPath = '/';
        $this->pluginConfig->dirUrl = '/';
        $this->pluginConfig->distDir = 'dist/';
        $this->pluginConfig->prefix = 'prefix';
    }

    public function testEnqueueAssets()
    {
        expect('wp_enqueue_style')
            ->once()
            ->with('prefix-blocks', '/dist/blocks.css', [], '', 'nonblocking');

        $assetCollector = new AssetCollector($this->pluginConfig);
        $assetCollector->enqueueAssets();
    }

    public function testEnqueueAssetsWithCriticalCss()
    {
        when('is_readable')->justReturn(false);
        when('filemtime')->returnArg();
        when('wp_enqueue_style')->returnArg();

        $assetCollector = new AssetCollector($this->pluginConfig);
        $assetCollector->enqueueAssets();

        $assetCollectorReflection = new ReflectionClass($assetCollector);

        $blockCollectorGetCriticalCss = $assetCollectorReflection->getMethod('getCriticalCss');
        $blockCollectorGetCriticalCss->setAccessible(true);

        $this->assertEquals($blockCollectorGetCriticalCss->invoke($assetCollector), '');
    }

    public function testEnqueueEditorAssets()
    {
        expect('wp_enqueue_script')
            ->once()
            ->with('prefix-blocks-editor', '/dist/editor.js', [], '', true);

        expect('wp_enqueue_style')
            ->once()
            ->with('prefix-blocks-editor', '/dist/editor.css', ['wp-edit-blocks'], '');

        $assetCollector = new AssetCollector($this->pluginConfig);
        $assetCollector->enqueueEditorAssets();
    }

    public function testEnqueueScripts()
    {
        expect('wp_enqueue_script')
            ->once()
            ->with('prefix-blocks', '/dist/blocks.js', [], '', true);

        $assetCollector = new AssetCollector($this->pluginConfig);
        $assetCollector->enqueueScripts();
    }
}