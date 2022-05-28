<?php

namespace KWIO\GutenbergBlocksFramework;

/**
 * Collects templates residing in the template directory.
 */
class TemplateCollector
{
    /**
     * The template directory.
     */
    private const TEMPLATE_FOLDER = 'post-type-templates';

    /**
     * Holds the configurated options.
     *
     * @var PluginConfigDTO
     */
    private PluginConfigDTO $pluginConfig;

    /**
     * @param PluginConfigDTO $pluginConfig The configured options.
     */
    public function __construct(PluginConfigDTO $pluginConfig)
    {
        $this->pluginConfig = $pluginConfig;
    }

    /**
     * Registers the templates.
     * @see Loader::int
     */
    public function registerTemplates(): void
    {
        $templates = glob($this->pluginConfig->dirPath . self::TEMPLATE_FOLDER . '/{,*/}*.php', GLOB_BRACE);
        foreach ($templates as $template) {
            $this->registerTemplate($template);
        }
    }

    /**
     * Registers a single template based on current post type.
     *
     * @param string $template Template file for directory name.
     */
    protected function registerTemplate(string $template): void
    {
        $postType = !empty($_GET['post_type']) ? $_GET['post_type'] : 'post';
        if (!$postTypeObj = get_post_type_object($postType)) {
            return;
        }

        $folder = basename(dirname($template));
        $isFolder = $folder !== self::TEMPLATE_FOLDER;
        $templateSlug = basename($template, '.php');

        if ($isFolder) {
            if ($folder !== $postType || empty($_GET['template']) || $_GET['template'] !== $templateSlug) {
                return;
            }
        } else {
            if ($templateSlug !== $postType) {
                return;
            }
        }

        $templateOptions = include $template;
        if (!empty($templateOptions['template'])) {
            $postTypeObj->template = $this->addNamespaceToBlockName($templateOptions['template']);
        }

        if (!empty($templateOptions['template_lock'])) {
            $postTypeObj->template_lock = $templateOptions['template_lock']; // phpcs:ignore
        }
    }

    /**
     * Adds the namespace if block is part of current namespace.
     *
     * @param array $template Array of nested blocks.
     *
     * @return array Array of nested blocks with namespace.
     */
    protected function addNamespaceToBlockName(array $template): array
    {
        return array_map(function ($block) {
            $block[0] = strpos($block[0], '/') === false ? $this->pluginConfig->prefix . '/' . $block[0] : $block[0];

            if (!empty($block[2]) && is_array($block[2])) {
                $block[2] = $this->addNamespaceToBlockName($block[2]);
            }

            return $block;
        }, $template);
    }
}