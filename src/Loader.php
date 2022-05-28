<?php

namespace KWIO\GutenbergBlocksFramework;

use Exception;
use KWIO\GutenbergBlocksFramework\View\PhpView;
use KWIO\GutenbergBlocksFramework\View\ViewInterface;

/**
 * Class to initialize the framework.
 */
final class Loader
{
    /**
     * Default whitelist of core blocks.
     */
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
        'core/separator',
        'core/html',
        'core/embed',
        'core/block',
        'core/media-text',
        'core/post-title'
    ];

    /**
     * Holds the configurated options.
     *
     * @var PluginConfigDTO
     */
    private PluginConfigDTO $pluginConfig;

    /**
     * Holds any defined custom categories.
     *
     * @var array
     */
    private array $categories = [];

    /**
     * @param string $file The filename of the plugin or theme (`__FILE__`).
     */
    public function __construct(string $file)
    {
        $this->pluginConfig = new PluginConfigDTO();
        $this->pluginConfig->blockWhitelist = self::CORE_BLOCK_WHITELIST;
        $this->pluginConfig->dirPath = plugin_dir_path($file);
        $this->pluginConfig->dirUrl = strpos($file, '/themes/') !== false ? get_template_directory_uri() . '/' : plugin_dir_url($file);
        $this->pluginConfig->distDir = 'dist/';
        $this->pluginConfig->prefix = preg_replace('/-gutenberg-blocks$/', '', basename(dirname($file)));
        $this->pluginConfig->viewClass = PhpView::class;
    }

    /**
     * Registers the blocks on the server-side.
     *
     * @param string $dir Blocks directory relative to the plugin or theme.
     * @param string $namespace Namespace of the block classes (`__NAMESPACE__`).
     *
     * @return Loader
     */
    public function loadBlocks(string $dir, string $namespace): Loader
    {
        $this->pluginConfig->blockDir = trailingslashit($dir);
        $this->pluginConfig->blockNamespace = $namespace;

        return $this;
    }

    /**
     * Defines an array of blocks that should be whitelisted.
     * Use `KWIO\GutenbergBlocksFramework\Loader::CORE_BLOCK_WHITELIST` and merge it with your array to extend the current whitelist.
     *
     * @param array $blockWhitelist Array of allowed block slugs.
     *
     * @return Loader
     */
    public function setBlockWhitelist(array $blockWhitelist): Loader
    {
        $this->pluginConfig->blockWhitelist = $blockWhitelist;

        return $this;
    }

    /**
     * Defines an array of custom block categories.
     * See [developer.wordpress.org](https://developer.wordpress.org/reference/hooks/block_categories_all/) for more.
     *
     * @param array $categories Array of category slugs.
     *
     * @return Loader
     */
    public function setCategories(array $categories): Loader
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Changes the path to the block assets dist folder.
     *
     * @param string $distDir Dist directory relative to the plugin or theme.
     *
     * @return Loader
     */
    public function setDistDir(string $distDir): Loader
    {
        $this->pluginConfig->distDir = trailingslashit($distDir);

        return $this;
    }

    /**
     * Sets the path of the directory of your translation file (e.g. kwio-de_DE.json) to translate strings in your custom block in the admin.
     * Make sure that the text domain matches this plugins prefix.
     *
     * @param string $path Full path to the languages directory.
     *
     * @return Loader
     */
    public function setTranslationsPath(string $path): Loader
    {
        $this->pluginConfig->translationsPath = trailingslashit($path);

        return $this;
    }

    /**
     * Implement a custom template engine or choose one of the follwing already implemented engines:
     *
     * - `KWIO\GutenbergBlocksFramework\View\PhpView` (default)
     * - `KWIO\GutenbergBlocksFramework\View\TwigView` (requires `twig/twig`)
     * - `KWIO\GutenbergBlocksFramework\View\TimberView` (requires `timber/timber`)
     *
     * @param string $viewClass String of a class extending `AbstractView`.
     *
     * @return Loader
     */
    public function setViewClass(string $viewClass): Loader
    {
        if (!is_subclass_of($viewClass, ViewInterface::class)) {
            throw new Exception("{$viewClass} should implement " . ViewInterface::class);
        }

        $this->pluginConfig->viewClass = $viewClass;

        return $this;
    }

    /**
     * Kick-starts the framework and sets up all the hooks. Should be the final method called.
     */
    public function init(): void
    {
        $assetCollector = new AssetCollector($this->pluginConfig);
        $blockCollector = new BlockCollector($this->pluginConfig);
        $templateCollector = new TemplateCollector($this->pluginConfig);

        add_action('load-post-new.php', [$templateCollector, 'registerTemplates']);
        add_filter('allowed_block_types_all', [$blockCollector, 'filterBlocks'], 10, 2);
        add_filter('block_categories_all', fn($categories) => array_merge($categories, $this->categories));
        add_action('enqueue_block_assets', [$assetCollector, 'enqueueAssets']);
        add_action('enqueue_block_editor_assets', [$assetCollector, 'enqueueEditorAssets']);
        add_action('init', [$blockCollector, 'registerBlocks']);
        add_action('wp_enqueue_scripts', [$assetCollector, 'enqueueScripts']);
    }
}