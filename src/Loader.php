<?php

namespace KWIO\GutenbergBlocksFramework;

use KWIO\GutenbergBlocksFramework\View\PhpView;
use KWIO\GutenbergBlocksFramework\View\ViewInterface;

final class Loader
{
    public const CORE_BLOCK_WHITELIST = [
        'core/image',
        'core/heading',
        'core/list',
        'core/video',
        'core/table',
        'core/code',
        'core/paragraph',
        'core/column',
        'core/columns',
        'core/group',
        'core/shortcode',
        'core/cover',
        'core-embed/youtube'
    ];

    private ?PluginConfigDTO $pluginConfig = null;

    public function __construct(string $file)
    {
        $this->pluginConfig = new PluginConfigDTO();
        $this->pluginConfig->blockWhitelist = self::CORE_BLOCK_WHITELIST;
        $this->pluginConfig->dirPath = plugin_dir_path($file);
        $this->pluginConfig->dirUrl = plugin_dir_url($file);
        $this->pluginConfig->distDir = 'dist/';
        $this->pluginConfig->prefix = preg_replace('/-gutenberg-blocks$/', '', basename(dirname($file)));
        $this->pluginConfig->viewClass = new PhpView();
    }

    public function loadBlocks(string $dir, string $namespace): Loader
    {
        $this->pluginConfig->blockDir = trailingslashit($dir);
        $this->pluginConfig->blockNamespace = $namespace;

        return $this;
    }

    public function setBlockWhitelist(array $blockWhitelist): Loader
    {
        $this->pluginConfig->blockWhitelist = $blockWhitelist;

        return $this;
    }

    public function setDistDir(string $distDir): Loader
    {
        $this->pluginConfig->distDir = trailingslashit($distDir);

        return $this;
    }

    public function setViewClass(ViewInterface $viewClass): Loader
    {
        $this->pluginConfig->viewClass = $viewClass;

        return $this;
    }

    public function init(): void
    {
        $assetCollector = new AssetCollector($this->pluginConfig);
        $blockCollector = new BlockCollector($this->pluginConfig);
        $templateCollector = new TemplateCollector($this->pluginConfig);

        add_action('admin_init', [$templateCollector, 'registerTemplates']);
        add_filter('allowed_block_types', [$blockCollector, 'filterBlocks']);
        add_filter('block_categories', [$blockCollector, 'groupBlocks']);
        add_action('enqueue_block_assets', [$assetCollector, 'enqueueAssets']);
        add_action('enqueue_block_editor_assets', [$assetCollector, 'enqueueEditorAssets']);
        add_action('init', [$blockCollector, 'registerBlocks']);
        add_action('wp_enqueue_scripts', [$assetCollector, 'enqueueScripts']);
    }
}