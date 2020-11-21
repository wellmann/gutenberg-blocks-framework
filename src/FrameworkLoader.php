<?php

namespace KWIO\GutenbergBlocksFramework;

final class FrameworkLoader
{
    private BlockCollector $blockCollector;
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
    private TemplateCollector $templateCollector;

    public function __construct(string $dirPath, string $namespace)
    {
        $this->dirPath = $dirPath;
        $this->blockCollector = new BlockCollector($dirPath, $namespace, $this->getPrefix(), $this->blockWhitelist);
        $this->templateCollector = new TemplateCollector($dirPath, $this->getPrefix());
    }

    public function setBlockWhitelist(array $blockWhitelist): FrameworkLoader
    {
        $this->blockWhitelist = $blockWhitelist;

        return $this;
    }

    public function init(): void
    {
        add_action('admin_init', [$this->templateCollector, 'registerTemplates']);
        add_filter('allowed_block_types', [$this->blockCollector, 'filterBlocks']);
        add_filter('block_categories', [$this->blockCollector, 'groupBlocks']);
        add_action('init', [$this->blockCollector, 'registerBlocks']);
    }

    protected function getPrefix(): string
    {
        $pluginDirName = basename($this->dirPath);

        return str_replace('-gutenberg-blocks', '', $pluginDirName);
    }
}