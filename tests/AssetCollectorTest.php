<?php

declare(strict_types=1);

namespace KWIO\GutenbergBlocks\Tests;

use KWIO\GutenbergBlocks\AssetCollector;
use KWIO\GutenbergBlocks\PluginConfigDTO;
use ReflectionClass;
use stdClass;

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

        $assetCollector = new AssetCollector($this->pluginConfig);
        $assetCollector->enqueueEditorAssets();
    }

    public function testEnqueueScripts()
    {
        $restUrl = html_entity_decode(json_encode('Url'), ENT_QUOTES, 'UTF-8');
        when('get_rest_url')->justReturn('Url');
        expect('wp_enqueue_script')
            ->once()
            ->with('prefix-blocks', '/dist/blocks.js', [], '', true);
        expect('wp_add_inline_script')
            ->once()
            ->with('prefix-blocks', "var Prefix = Prefix || {};\nPrefix.apiRoot = {$restUrl};", 'before');

        $assetCollector = new AssetCollector($this->pluginConfig);
        $assetCollector->enqueueScripts();
    }

    public function testEnqueueEditorTranslations()
    {
        when('is_readable')->justReturn(true);
        when('file_get_contents')->justReturn('localeData');

        $domain = $this->pluginConfig->prefix;
        $localeData = 'localeData';

        expect('wp_add_inline_script')
            ->once()
            ->with(
                'prefix-blocks-editor',
                <<<JS
( function( domain, translations ) {
    var localeData = translations.locale_data[ domain ] || translations.locale_data.messages;
    localeData[""].domain = domain;
    wp.i18n.setLocaleData( localeData, domain );
} )( "{$domain}", {$localeData} );
JS,
                'before'
            );

        $assetCollector = new AssetCollector($this->pluginConfig);
        $assetCollectorReflection = new ReflectionClass($assetCollector);

        $blockCollectorEnqueueEditorTranslations = $assetCollectorReflection->getMethod('enqueueEditorTranslations');
        $blockCollectorEnqueueEditorTranslations->setAccessible(true);
        $blockCollectorEnqueueEditorTranslations->invoke($assetCollector);
    }
}