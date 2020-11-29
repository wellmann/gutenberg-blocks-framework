<?php

namespace KWIO\GutenbergBlocksFramework;

use Exception;

class BlockCollector
{
    private array $blocks = [];
    private array $blockWhitelist = [];
    private string $dirPath = '';
    private string $namespace = '';
    private string $prefix = '';

    public function __construct(string $dirPath, string $namespace, string $prefix, array $blockWhitelist)
    {
        $this->blockWhitelist = $blockWhitelist;
        $this->dirPath = $dirPath;
        $this->namespace = $namespace;
        $this->prefix = $prefix;
    }

    public function filterBlocks(): array
    {
        return array_merge($this->blockWhitelist, $this->blocks);
    }

    public function groupBlocks(array $categories): array
    {
        return array_merge($categories, [
            [
                'slug' => $this->prefix,
                'title' => ucwords($this->prefix, '-')
            ],
            [
                'slug' => 'wordpress-default',
                'title' => 'WordPress'
            ]
        ]);
    }

    public function registerBlocks(): void
    {
        $blocks = glob($this->dirPath . 'src/*', GLOB_ONLYDIR);
        foreach ($blocks as $block) {
            $block = basename($block);
            $this->registerBlock($block);
        }
    }

    protected function registerBlock(string $block): void
    {
        $className = BaseBlock::class;

        // Check if block has a dedicated PHP class.
        $classPath = $this->dirPath . "src/{$block}/block.php";
        if (file_exists($classPath)) {
            require_once $classPath;
            $className = $this->namespace . '\\' . str_replace('-', '', ucwords($block, '-'));
        }

        $classInstance = new $className($block, $this->dirPath);
        if (!$classInstance instanceof BaseBlock) {
            throw new Exception($className . ' must be an instance of ' . BaseBlock::class);
        }

        $name = $this->prefix . '/' . $block;

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