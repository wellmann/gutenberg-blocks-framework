<?php

namespace KWIO\GutenbergBlocks;

/**
 * Holds the configured options.
 */
class PluginConfigDTO
{
    /**
     * Blocks directory relative to the plugin or theme.
     * @see Loader::loadBlocks
     *
     * @var string
     */
    public string $blockDir = '';

    /**
     * Namespace of the block classes (`__NAMESPACE__`).
     * @see Loader::loadBlocks
     *
     * @var string
     */
    public string $blockNamespace = '';

    /**
     * Array of blocks that should be whitelisted.
     * @see Loader::setBlockWhitelist
     *
     * @var array
     */
    public array $blockWhitelist = [];

    /**
     * The filesystem directory path for the theme or plugin __FILE__ passed in.
     * @see Loader::__construct
     *
     * @var string
     */
    public string $dirPath = '';

    /**
     * The URL directory path for the theme or plugin __FILE__ passed in.
     * @see Loader::__construct
     *
     * @var string
     */
    public string $dirUrl = '';

    /**
     * The path to the block assets dist folder.
     * @see Loader::setDistDir
     *
     * @var string
     */
    public string $distDir = '';

    /**
     * The theme or plugin prefix (e.g. `kwio` from `kwio-gutenberg-blocks` plugin or `kwio-theme` from `kwio-theme`).
     * @see Loader::__construct
     * @see Loader::setDistDir
     *
     * @var string
     */
    public string $prefix = '';

    /**
     * The path of the directory of your translation file (e.g. kwio-de_DE.json).
     * @see Loader::setTranslationsPath
     *
     * @var string
     */
    public string $translationsPath = '';

    /**
     * String of a class extending `AbstractView`.
     * @see Loader::__construct
     * @see Loader::setViewClass
     *
     * @var string
     */
    public string $viewClass;
}