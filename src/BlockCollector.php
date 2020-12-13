<?php

namespace KWIO\GutenbergBlocksFramework;

use Exception;

class BlockCollector
{
    private array $blocks = [];
    private string $blockDirPath = '';
    private ?object $pluginConfig = null;

    public function __construct(object $pluginConfig)
    {
        $this->blockDirPath = $pluginConfig->dirPath . $pluginConfig->blockDir;
        $this->pluginConfig = $pluginConfig;
    }

    public function filterBlocks(): array
    {
        return array_merge($this->pluginConfig->blockWhitelist, $this->blocks);
    }

    public function groupBlocks(array $categories): array
    {
        return array_merge($categories, [
            [
                'slug' => $this->pluginConfig->prefix,
                'title' => ucwords($this->pluginConfig->prefix, '-')
            ],
            [
                'slug' => 'wordpress-default',
                'title' => 'WordPress'
            ]
        ]);
    }

    public function registerBlocks(): void
    {
        $blocks = glob($this->blockDirPath . '*', GLOB_ONLYDIR);
        foreach ($blocks as $block) {
            $block = basename($block);
            $this->registerBlock($block);
        }
    }

    protected function registerBlock(string $block): void
    {
        $className = BaseBlock::class;

        // Check if block has a dedicated PHP class.
        $classPath = $this->blockDirPath . $block . '/block.php';
        if (file_exists($classPath)) {
            require_once $classPath;
            $className = $this->pluginConfig->blockNamespace . '\\' . str_replace('-', '', ucwords($block, '-'));
        }

        $classInstance = new $className($block, $this->blockDirPath);
        if (!$classInstance instanceof BaseBlock) {
            throw new Exception($className . ' must be an instance of ' . BaseBlock::class);
        }

        $name = $this->pluginConfig->prefix . '/' . $block;

        // Override core blocks render output.
        if (strpos($block, 'core-') === 0) {
            $name = 'core/' . preg_replace('/^core-/', '', $block);
            unregister_block_type($name);
        }

        if (property_exists($classInstance, 'showOn')) {
            foreach ($classInstance->showOn as $postType) {
                $this->restrictedBlocks[$postType][] = $name;
            }
        }

        $args = ['render_callback' => [$classInstance, 'render']];

        if (!empty($classInstance->getAttributes())) {
            $args['attributes'] = $classInstance->getAttributes();
        }

        if (register_block_type($name, $args)) {
            $this->blocks[] = $name;
        }
    }
}