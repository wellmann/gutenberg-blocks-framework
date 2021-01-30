<?php

namespace KWIO\GutenbergBlocksFramework;

class TemplateCollector
{
    private ?PluginConfigDTO $pluginConfig;

    public function __construct(PluginConfigDTO $pluginConfig)
    {
        $this->pluginConfig = $pluginConfig;
    }

    public function registerTemplates(): void
    {
        foreach ($this->getTemplates() as $template) {
            $this->registerTemplate($template);
        }
    }

    protected function registerTemplate(string $template): void
    {
        $postType = !empty($_GET['post_type']) ? $_GET['post_type'] : 'post';
        if (!$postTypeObj = get_post_type_object($postType)) {
            return;
        }

        $folder = basename(dirname($template));
        $isFolder = $folder !== 'templates';
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
            $postTypeObj->template_lock = $templateOptions['templateLock']; // phpcs:ignore
        }
    }

    protected function addNamespaceToBlockName(array $template): array
    {
        return array_map(function (array $block): array {
            $block[0] = strpos($block[0], '/') === false ? $this->pluginConfig->prefix . '/' . $block[0] : $block[0];

            if (!empty($block[2]) && is_array($block[2])) {
                $block[2] = $this->addNamespaceToBlockName($block[2]);
            }

            return $block;
        }, $template);
    }

    protected function getTemplates(): array
    {
        return glob($this->pluginConfig->dirPath . 'templates/{,*/}*.php', GLOB_BRACE);
    }
}