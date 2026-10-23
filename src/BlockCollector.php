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
     * Holds the path to the blocks src directory.
     *
     * @var string
     */
    private string $blockSrcDirPath;

    /**
     * Holds the path to the blocks dist directory.
     *
     * @var string
     */
    private string $blockDistDirPath;

    /**
     * Holds the configured options.
     *
     * @var Config
     */
    private Config $config;

    /**
     * Holds blocks that are restricted to specific post types.
     *
     * @var array
     */
    private array $restrictedBlocks = [];

    /**
     * @param Config $config The configured options.
     */
    public function __construct(Config $config)
    {
        $this->blockSrcDirPath = $config->dirPath . $config->blockDir;
        $this->blockDistDirPath = $config->dirPath . $config->distDir;
        $this->config = $config;
    }

    /**
     * Removes blocks from block selector if they are not eligible for display on current post type.
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
        $manifestFile = $this->config->blocksManifest ?: $this->config->distDir . 'blocks-manifest.php';
        $manifestPath = $this->config->dirPath . $manifestFile;
        if (is_readable($manifestPath) && function_exists('wp_register_block_metadata_collection')) {
            wp_register_block_metadata_collection($this->blockDistDirPath, $manifestPath);
        }

        $blocks = glob($this->blockSrcDirPath . '*', GLOB_ONLYDIR);
        foreach ($blocks as $block) {
            $block = basename($block);
            if (!str_starts_with($block, '_')) {
                $this->registerBlock($block);
            }
        }
    }

    /**
     * Registers a single block.
     * @see BlockCollector::registerBlocks
     *
     * @param string $block Block name without namespace.
     *
     * @throws Exception
     */
    protected function registerBlock(string $block): void
    {
        $blockClassName = str_replace('-', '', ucwords($block, '-'));
        $blockFullClassName = BaseBlock::class;
        $blockPath = trailingslashit($this->blockDistDirPath . $block);

        // Check if block has a dedicated PHP class.
        if (file_exists($blockPath . $blockClassName . '.php')) {
            require_once $blockPath . $blockClassName . '.php';
            $blockFullClassName = $this->config->classNamespace . '\\' . $blockClassName;
        }

        $classInstance = new $blockFullClassName($block, $this->blockDistDirPath, $this->config);
        if (!$classInstance instanceof BaseBlock) {
            throw new Exception($blockFullClassName . ' must be an instance of ' . BaseBlock::class);
        }

        $name = $this->config->namespace . '/' . $block;

        // Override core blocks render output.
        if (str_starts_with($block, 'core-')) {
            $name = 'core/' . preg_replace('/^core-/', '', $block);

            add_filter('render_block', function ($blockContent, $block) use ($classInstance, $name) {
                if ($block['blockName'] !== $name) {
                    return $blockContent;
                }

                return call_user_func_array([$classInstance, 'render'], [$block['attrs'], $blockContent]);
            }, 10, 2);

            return;
        }

        $blockClassReflection = new ReflectionClass($blockFullClassName);
        $visibilityAttributes = $blockClassReflection->getAttributes(Visibility::class);
        foreach ($visibilityAttributes as $visibilityAttribute) {
            $visibility = $visibilityAttribute->newInstance();

            foreach ($visibility->postTypes as $postType) {
                $this->restrictedBlocks[$postType][] = $name;
            }
        }

        $blockType = register_block_type_from_metadata($blockPath, [
            'name' => $name,
            'render_callback' => [$classInstance, 'render']
        ]);

        if ($blockType) {
            $this->blocks[] = $name;
        }
    }
}