<?php

declare(strict_types=1);

namespace KWIO\GutenbergBlocks\Tests;

use KWIO\GutenbergBlocks\AssetCollector;
use KWIO\GutenbergBlocks\PluginConfigDTO;
use ReflectionClass;

use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Functions\when;

class AssetCollectorTest extends TestCase
{
    protected ?PluginConfigDTO $pluginConfig = null;

    protected function setUp(): void
    {
        parent::setUp();

        when('get_locale')->justReturn('de_DE');
        when('is_admin')->justReturn(false);

        $this->pluginConfig = new PluginConfigDTO();
        $this->pluginConfig->dirPath = '/';
        $this->pluginConfig->dirUrl = '/';
        $this->pluginConfig->distDir = 'dist/';
        $this->pluginConfig->prefix = 'prefix';
    }

    public function testAddEditorStylesIfIsTheme()
    {
        $this->pluginConfig->isTheme = true;

        expect('add_editor_style')
            ->once()
            ->with('dist/editor.css');

        $assetCollector = new AssetCollector($this->pluginConfig);
        $assetCollector->addEditorStyles();
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
        $this->pluginConfig->isTheme = true;

        expect('wp_enqueue_script')
            ->once()
            ->with('prefix-blocks-editor', '/dist/editor.js', [], '', true);

        expect('wp_set_script_translations')
            ->once()
            ->with('prefix-blocks-editor', 'prefix', '');

        $assetCollector = new AssetCollector($this->pluginConfig);
        $assetCollector->enqueueEditorAssets();
    }

    public function testEnqueueEditorAssetsWhenNotTheme()
    {
        $this->pluginConfig->isTheme = false;

        expect('wp_enqueue_script')
            ->once()
            ->with('prefix-blocks-editor', '/dist/editor.js', [], '', true);

        expect('wp_set_script_translations')
            ->once()
            ->with('prefix-blocks-editor', 'prefix', '');

        expect('wp_enqueue_style')
            ->once()
            ->with('prefix-blocks-editor', '/dist/editor.css', ['wp-edit-blocks'], '');

        $assetCollector = new AssetCollector($this->pluginConfig);
        $assetCollector->enqueueEditorAssets();
    }

    public function testEnqueueScripts()
    {
        when('get_rest_url')->justReturn('Url');
        expect('wp_enqueue_script')
            ->once()
            ->with('prefix-blocks', '/dist/blocks.js', [], '', true);

        $assetCollector = new AssetCollector($this->pluginConfig);
        $assetCollector->enqueueScripts();
    }
}