<?php

declare(strict_types=1);

namespace KWIO\GutenbergBlocks\Tests;

use KWIO\GutenbergBlocks\AssetCollector;
use KWIO\GutenbergBlocks\Config;
use ReflectionClass;

use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Functions\when;

class AssetCollectorTest extends TestCase
{
    protected ?Config $config = null;

    protected function setUp(): void
    {
        parent::setUp();

        when('get_locale')->justReturn('de_DE');
        when('is_admin')->justReturn(false);
        when('file_exists')->alias(function($arg) {
            switch($arg) {
                case '/dist/editor.asset.php':
                case '/dist/blocks.asset.php':
                    return false;
                default:
                    return true;
            }
        });

        $this->config = new Config();
        $this->config->dirPath = '/';
        $this->config->dirUrl = '/';
        $this->config->distDir = 'dist/';
        $this->config->namespace = 'namespace';
    }

    public function testAddEditorStylesIfIsTheme()
    {
        $this->config->isTheme = true;

        expect('add_editor_style')
            ->once()
            ->with('dist/editor.css');

        $assetCollector = new AssetCollector($this->config);
        $assetCollector->addEditorStyles();
    }

    public function testEnqueueAssets()
    {
        expect('wp_enqueue_style')
            ->once()
            ->with('namespace-blocks', '/dist/blocks.css', [], '', 'nonblocking');

        $assetCollector = new AssetCollector($this->config);
        $assetCollector->enqueueAssets();
    }

    public function testEnqueueAssetsWithCriticalCss()
    {
        when('is_readable')->justReturn(false);
        when('filemtime')->returnArg();
        when('wp_enqueue_style')->returnArg();

        $assetCollector = new AssetCollector($this->config);
        $assetCollector->enqueueAssets();

        $assetCollectorReflection = new ReflectionClass($assetCollector);

        $blockCollectorGetCriticalCss = $assetCollectorReflection->getMethod('getCriticalCss');
        $blockCollectorGetCriticalCss->setAccessible(true);

        $this->assertEquals($blockCollectorGetCriticalCss->invoke($assetCollector), '');
    }

    public function testEnqueueEditorAssets()
    {
        $this->config->isTheme = true;

        expect('wp_enqueue_script')
            ->once()
            ->with('namespace-blocks-editor', '/dist/editor.js', [], '', true);

        expect('wp_set_script_translations')
            ->once()
            ->with('namespace-blocks-editor', 'namespace', '');

        $assetCollector = new AssetCollector($this->config);
        $assetCollector->enqueueEditorAssets();
    }

    public function testEnqueueEditorAssetsWhenNotTheme()
    {
        $this->config->isTheme = false;

        expect('wp_enqueue_script')
            ->once()
            ->with('namespace-blocks-editor', '/dist/editor.js', [], '', true);

        expect('wp_set_script_translations')
            ->once()
            ->with('namespace-blocks-editor', 'namespace', '');

        expect('wp_enqueue_style')
            ->once()
            ->with('namespace-blocks-editor', '/dist/editor.css', ['wp-edit-blocks'], '');

        $assetCollector = new AssetCollector($this->config);
        $assetCollector->enqueueEditorAssets();
    }

    public function testEnqueueScripts()
    {
        when('get_rest_url')->justReturn('Url');
        expect('wp_enqueue_script')
            ->once()
            ->with('namespace-blocks', '/dist/blocks.js', [], '', true);

        $assetCollector = new AssetCollector($this->config);
        $assetCollector->enqueueScripts();
    }
}