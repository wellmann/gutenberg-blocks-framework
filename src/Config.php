<?php

declare(strict_types=1);

namespace KWIO\GutenbergBlocks;

/**
 * Holds the configured options.
 */
class Config
{
    /**
     * Blocks directory relative to the plugin or theme.
     * @see Loader::loadBlocks
     *
     * @var string
     */
    public string $blockDir = '';

    /**
     * Array of blocks that should be whitelisted.
     * @see Loader::setBlockWhitelist
     *
     * @var array
     */
    public array $blockWhitelist = [];

    /**
     * Namespace of the block classes (`__NAMESPACE__`).
     * @see Loader::loadBlocks
     *
     * @var string
     */
    public string $classNamespace = '';

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
     * If library is used in plugin or theme.
     * @see Loader::__construct
     *
     * @var boolean
     */
    public bool $isTheme;

    /**
     * The theme or plugin name.
     * @see Loader::__construct
     * @see Loader::setDistDir
     *
     * @var string
     */
    public string $namespace = '';

    /**
     * The path of the directory of your translation file (e.g. kwio-de_DE.json).
     * @see Loader::setTranslationsPath
     *
     * @var string
     */
    public string $translationsPath = '';

    /**
     * Absolute path to a custom cache directory for twig or blade views.
     * @see Loader::loadBlocks
     *
     * @var string|null
     */
    public ?string $viewCachePath = null;

    /**
     * String of a class extending `AbstractView`.
     * @see Loader::__construct
     * @see Loader::setViewClass
     *
     * @var string
     */
    public string $viewClass;
}