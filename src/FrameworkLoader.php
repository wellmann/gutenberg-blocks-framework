<?php

namespace KWIO\GutenbergBlocksFramework;

final class FrameworkLoader
{
    private BlockCollector $blockCollector;
    private TemplateCollector $templateCollector;
    private string $dirPath = '';
    private string $namespace = '';
    private string $prefix = '';

    public function __construct()
    {
        $this->blockCollector = new BlockCollector();
        $this->templateCollector = new TemplateCollector();
    }

    public function setDirPath(string $dirPath): FrameworkLoader
    {
        $this->dirPath = $dirPath;

        return $this;
    }

    public function setNamespace(string $namespace): FrameworkLoader
    {
        $this->namespace = $namespace;
        $this->prefix = explode('\\', $this->namespace)[0];

        return $this;
    }

    public function init(): void
    {
        add_action('init', [$this, 'registerBlocks']);
        add_action('admin_init', [$this, 'registerTemplates']);
        add_filter('allowed_block_types', [$this, 'filterBlocks']);
        add_filter('block_categories', [$this, 'groupBlocks']);
    }

    public function registerBlocks(): void
    {
        $this->blockCollector->setDirPath($this->dirPath);
        $this->blockCollector->setNamespace($this->namespace);
        $this->blockCollector->setPrefix($this->prefix);

        $blocks = glob($this->dirPath . '/src/*', GLOB_ONLYDIR);
        foreach ($blocks as $block) {
            $block = basename($block);
            $this->blockCollector->register($block);
        }
    }

    public function registerTemplates(): void
    {
        $this->templateCollector->setPrefix($this->prefix);

        $templates = glob($this->dirPath . '/templates/*.php');
        foreach ($templates as $template) {
            $this->tempateCollector->register($template);
        }
    }

    public function filterBlocks(): array
    {
        $coreBlockWhitelist = [
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

        return array_merge($coreBlockWhitelist, $this->blockCollector->getBlocks());
    }

    public function groupBlocks(array $categories): array
    {
        return array_merge($categories, [
            [
                'slug' => $this->prefix,
                'title' => ucwords($this->prefix)
            ],
            [
                'slug' => 'wordpress-default',
                'title' => 'WordPress'
            ]
        ]);
    }
}