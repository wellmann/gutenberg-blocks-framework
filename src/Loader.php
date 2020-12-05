<?php

namespace KWIO\GutenbergBlocksFramework;

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
        'core-embed/youtube'
    ];

    private array $blockWhitelist = [];
    private string $dirPath = '';
    private string $namespace = '';

    public function __construct(string $dirPath, string $dirUrl, string $namespace)
    {
        $this->blockWhitelist = self::CORE_BLOCK_WHITELIST;
        $this->dirPath = $dirPath;
        $this->dirUrl = $dirUrl;
        $this->namespace = $namespace;
    }

    public function setBlockWhitelist(array $blockWhitelist): Loader
    {
        $this->blockWhitelist = $blockWhitelist;

        return $this;
    }

    public function init(): void
    {
        $assetCollector = new AssetCollector(
            $this->dirPath,
            $this->dirUrl,
            $this->getPrefix()
        );
        $blockCollector = new BlockCollector(
            $this->dirPath,
            $this->namespace,
            $this->getPrefix(),
            $this->blockWhitelist
        );
        $templateCollector = new TemplateCollector($this->dirPath, $this->getPrefix());

        add_action('admin_init', [$templateCollector, 'registerTemplates']);
        add_filter('allowed_block_types', [$blockCollector, 'filterBlocks']);
        add_filter('block_categories', [$blockCollector, 'groupBlocks']);
        add_action('enqueue_block_assets', [$assetCollector, 'enqueueAssets']);
        add_action('enqueue_block_editor_assets', [$assetCollector, 'enqueueEditorAssets']);
        add_action('init', [$blockCollector, 'registerBlocks']);
        add_action('wp_enqueue_scripts', [$assetCollector, 'enqueueScripts']);
    }

    protected function getPrefix(): string
    {
        $pluginDirName = basename($this->dirPath);

        return str_replace('-gutenberg-blocks', '', $pluginDirName);
    }
}