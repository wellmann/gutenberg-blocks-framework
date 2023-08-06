<?php

declare(strict_types=1);

namespace KWIO\GutenbergBlocks;

use Exception;
use KWIO\GutenbergBlocks\View\PhpView;
use KWIO\GutenbergBlocks\View\ViewInterface;

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
        'core/list-item',
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
        'core/post-title',
        'core/spacer'
    ];

    /**
     * Holds the configurated options.
     *
     * @var Config
     */
    private Config $config;

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
        $this->config = new Config();
        $this->config->blockWhitelist = self::CORE_BLOCK_WHITELIST;
        $this->config->isTheme = strpos($file, '/themes/') !== false;
        $this->config->dirPath = plugin_dir_path($file);
        $this->config->dirUrl = $this->config->isTheme ? trailingslashit(get_stylesheet_directory_uri()) : plugin_dir_url($file);
        $this->config->distDir = 'dist/';
        $this->config->prefix = preg_replace(['/-theme$/', '/-gutenberg-blocks$/'], '', basename(dirname($file)));
        $this->config->viewClass = PhpView::class;
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
        $this->config->blockDir = trailingslashit($dir);
        $this->config->blockNamespace = $namespace;

        return $this;
    }

    /**
     * Defines an array of blocks that should be whitelisted.
     * Use `KWIO\GutenbergBlocks\Loader::CORE_BLOCK_WHITELIST` and merge it with your array to extend the current whitelist.
     *
     * @param array $blockWhitelist Array of allowed block slugs.
     *
     * @return Loader
     */
    public function setBlockWhitelist(array $blockWhitelist): Loader
    {
        $this->config->blockWhitelist = $blockWhitelist;

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
        $this->config->distDir = trailingslashit($distDir);

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
        $this->config->translationsPath = trailingslashit($path);

        return $this;
    }

    public function setViewCachePath(string $path): Loader
    {
        $this->config->viewCachePath = trailingslashit($path);

        return $this;
    }

    /**
     * Implement a custom template engine or choose one of the follwing already implemented engines:
     *
     * - `KWIO\GutenbergBlocks\View\PhpView` (default)
     * - `KWIO\GutenbergBlocks\View\TwigView` (requires `twig/twig`)
     * - `KWIO\GutenbergBlocks\View\TimberView` (requires `timber/timber`)
     * - `KWIO\GutenbergBlocks\View\BladeOneView` (requires `eftec/bladeone`)
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

        $this->config->viewClass = $viewClass;

        return $this;
    }

    /**
     * Kick-starts the framework and sets up all the hooks. Should be the final method called.
     */
    public function init(): void
    {
        $assetCollector = new AssetCollector($this->config);
        $blockCollector = new BlockCollector($this->config);
        $templateCollector = new TemplateCollector($this->config);

        add_action('load-post-new.php', [$templateCollector, 'registerTemplates']);
        add_filter('allowed_block_types_all', [$blockCollector, 'filterBlocks'], 10, 2);
        add_filter('block_categories_all', fn($categories) => array_merge($categories, $this->categories));
        add_action('enqueue_block_assets', [$assetCollector, 'enqueueAssets']);
        add_action('enqueue_block_editor_assets', [$assetCollector, 'enqueueEditorAssets']);
        add_action('after_setup_theme', [$assetCollector, 'addEditorStyles']);
        add_action('init', [$blockCollector, 'registerBlocks']);
        add_action('wp_enqueue_scripts', [$assetCollector, 'enqueueScripts']);
    }
}