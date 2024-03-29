<?php

declare(strict_types=1);

namespace KWIO\GutenbergBlocks;

use Exception;
use KWIO\GutenbergBlocks\Attribute\Visibility;
use ReflectionClass;
use WP_Block_Editor_Context;

/**
 * Collects the blocks residing in the blocks directory.
 */
class BlockCollector
{
    /**
     * Holds all successfully registered blocks.
     *
     * @var array
     */
    private array $blocks = [];

    /**
     * Holds the path to the blocks directoy.
     *
     * @var string
     */
    private string $blockDirPath;

    /**
     * Holds the configurated options.
     *
     * @var Config
     */
    private Config $config;

    /**
     * Holds blocks that are restricted to speciffic post types.
     *
     * @var array
     */
    private array $restrictedBlocks = [];

    /**
     * @param Config $config The configured options.
     */
    public function __construct(Config $config)
    {
        $this->blockDirPath = $config->dirPath . $config->blockDir;
        $this->config = $config;
    }

    /**
     * Removes blocks from block selector if they are not elligible for display on current post type.
     * @see Loader::int
     *
     * @param bool|array $allowedBlockTypes  Array of block type slugs, or boolean to enable/disable all. Default true (all registered block types supported).
     * @param WP_Block_Editor_Context $blockEditorContext The current block editor context.
     */
    public function filterBlocks($allowedBlockTypes, WP_Block_Editor_Context $blockEditorContext)
    {
        if (empty($this->config->blockWhitelist)) {
            return $allowedBlockTypes;
        }

        $allowedBlockTypes = array_merge($this->config->blockWhitelist, $this->blocks);

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

    /**
     * Registers the blocks.
     * @see Loader::int
     */
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

    /**
     * Registers a single block.
     * @see BlockCollector::registerBlocks
     *
     * @param string $block Block name without namespace.
     */
    protected function registerBlock(string $block): void
    {
        $blockClassName = str_replace('-', '', ucwords($block, '-'));
        $blockFullClassName = BaseBlock::class;
        $blockPath = trailingslashit($this->blockDirPath . $block);

        // Check if block has a dedicated PHP class.
        if (file_exists($blockPath . $blockClassName . '.php')) {
            require_once $blockPath . $blockClassName . '.php';
            $blockFullClassName = $this->config->classNamespace . '\\' . $blockClassName;
        }

        $classInstance = new $blockFullClassName($block, $this->blockDirPath, $this->config);
        if (!$classInstance instanceof BaseBlock) {
            throw new Exception($blockFullClassName . ' must be an instance of ' . BaseBlock::class);
        }

        $name = $this->config->namespace . '/' . $block;

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

        // Visibility declarion via PHP 8 attributes.
        if (version_compare(PHP_VERSION, '8.0.0') >= 0) {
            $blockClassReflection = new ReflectionClass($blockFullClassName);
            $visibilityAttributes = $blockClassReflection->getAttributes(Visibility::class);
            foreach ($visibilityAttributes as $visibilityAttribute) {
                $visibility = $visibilityAttribute->newInstance();

                foreach ($visibility->postTypes as $postType) {
                    $this->restrictedBlocks[$postType][] = $name;
                }
            }
        }

        $args = [
            'name' => $name,
            'apiVersion' => 2,
            'render_callback' => [$classInstance, 'render']
        ];

        $blockJson = $blockPath . 'block.json';
        $blockJsonData = wp_json_file_decode($blockJson, ['associative' => true]);
        if (is_array($blockJsonData)) {
            $args = array_merge($blockJsonData, $args);
        }

        if (register_block_type($name, $args)) {
            $this->blocks[] = $name;
        }
    }
}