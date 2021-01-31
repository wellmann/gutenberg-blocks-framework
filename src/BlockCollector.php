<?php

namespace KWIO\GutenbergBlocksFramework;

use Exception;

class BlockCollector
{
    private array $blocks = [];
    private string $blockDirPath;
    private PluginConfigDTO $pluginConfig;

    public function __construct(PluginConfigDTO $pluginConfig)
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
                'title' => str_replace('-', ' ', ucwords($this->pluginConfig->prefix, '-'))
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
            if (strpos($block, '_') !== 0) {
                $this->registerBlock($block);
            }
        }
    }

    protected function registerBlock(string $block): void
    {
        $blockClassName = str_replace('-', '', ucwords($block, '-'));
        $blockFullClassName = BaseBlock::class;
        $blockPath = trailingslashit($this->blockDirPath . $block);

        // Check if block has a dedicated PHP class.
        if (file_exists($blockPath . $blockClassName . '.php') || file_exists($blockPath . 'block.php')) {
            require_once file_exists($blockPath . 'block.php') ? $blockPath . 'block.php' : $blockPath . $blockClassName . '.php';
            $blockFullClassName = $this->pluginConfig->blockNamespace . '\\' . $blockClassName;
        }

        $classInstance = new $blockFullClassName($block, $this->blockDirPath, $this->pluginConfig->viewClass);
        if (!$classInstance instanceof BaseBlock) {
            throw new Exception($blockFullClassName . ' must be an instance of ' . BaseBlock::class);
        }

        $name = $this->pluginConfig->prefix . '/' . $block;

        // Override core blocks render output.
        if (strpos($block, 'core-') === 0) {
            $name = 'core/' . preg_replace('/^core-/', '', $block);

            add_filter('render_block', function ($blockContent, $block) use ($classInstance, $name) {
                if ($block['blockName'] !== $name) {
                    return $blockContent;
                }

                return call_user_func_array([$classInstance, 'render'], [$block['attrs'], $blockContent]);
            }, 10, 2);

            return;
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