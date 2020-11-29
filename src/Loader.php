<?php

namespace KWIO\GutenbergBlocksFramework;

final class Loader
{
    private array $blockWhitelist = [
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
        'core/shortcode'
    ];
    private string $dirPath = '';
    private string $namespace = '';

    public function __construct(string $dirPath, string $namespace)
    {
        $this->dirPath = $dirPath;
        $this->namespace = $namespace;
    }

    public function setBlockWhitelist(array $blockWhitelist): Loader
    {
        $this->blockWhitelist = $blockWhitelist;

        return $this;
    }

    public function init(): void
    {
        $blockCollector = new BlockCollector($this->dirPath, $this->namespace, $this->getPrefix(), $this->blockWhitelist);
        $templateCollector = new TemplateCollector($this->dirPath, $this->getPrefix());

        add_action('admin_init', [$templateCollector, 'registerTemplates']);
        add_filter('allowed_block_types', [$blockCollector, 'filterBlocks']);
        add_filter('block_categories', [$blockCollector, 'groupBlocks']);
        add_action('init', [$blockCollector, 'registerBlocks']);
    }

    protected function getPrefix(): string
    {
        $pluginDirName = basename($this->dirPath);

        return str_replace('-gutenberg-blocks', '', $pluginDirName);
    }
}