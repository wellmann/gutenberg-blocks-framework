<?php

namespace KWIO\GutenbergBlocksFramework;

use Exception;
use WP_Block_Editor_Context;

class BlockCollector
{
    private array $blocks = [];
    private string $blockDirPath;
    private PluginConfigDTO $pluginConfig;
    private array $restrictedBlocks = [];

    public function __construct(PluginConfigDTO $pluginConfig)
    {
        $this->blockDirPath = $pluginConfig->dirPath . $pluginConfig->blockDir;
        $this->pluginConfig = $pluginConfig;
    }

    public function filterBlocks($allowedBlockTypes, WP_Block_Editor_Context $blockEditorContext)
    {
        $allowedBlockTypes = array_merge($this->pluginConfig->blockWhitelist, $this->blocks);

        if (empty($blockEditorContext->post)) {
            return $allowedBlockTypes;
        }

        foreach ($this->restrictedBlocks as $postType => $blocks) {
            if ($blockEditorContext->post->post_type === $postType) { // phpcs:ignore
                continue;
            }

            foreach ($blocks as $block) {
                $key = array_search($block, $allowedBlockTypes);
                unset($allowedBlockTypes[$key]);
            }
        }

        return array_values($allowedBlockTypes);
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

        $classInstance = new $blockFullClassName($block, $this->blockDirPath, $this->pluginConfig);
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

        // Check if block has limited visibility.
        $showOnPostTypeConstant = $blockFullClassName . '::SHOW_ON_POST_TYPE';
        if (defined($showOnPostTypeConstant)) {
            $showOnPostTypeConstantValue = constant($showOnPostTypeConstant);
            if (!is_array($showOnPostTypeConstantValue)) {
                return;
            }

            foreach ($showOnPostTypeConstantValue as $postType) {
                $this->restrictedBlocks[$postType][] = $name;
            }
        }

        $args = ['render_callback' => [$classInstance, 'render']];

        if (!empty($classInstance->getAttributes())) {
            $args['attributes'] = $classInstance->getAttributes();
        }

        if (!empty($classInstance->getMetaData())) {
            $args = array_merge($classInstance->getMetaData(), $args);
        }

        if (register_block_type($name, $args)) {
            $this->blocks[] = $name;
        }
    }
}