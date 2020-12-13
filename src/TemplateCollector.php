<?php

namespace KWIO\GutenbergBlocksFramework;

class TemplateCollector
{
    private ?object $pluginConfig = null;

    public function __construct(object $pluginConfig)
    {
        $this->pluginConfig = $pluginConfig;
    }

    public function registerTemplates(): void
    {
        $templates = glob($this->pluginConfig->dirPath . '/templates/*.php');
        foreach ($templates as $template) {
            $this->registerTemplate($template);
        }
    }

    protected function registerTemplate(string $template): void
    {
        if (basename($_SERVER['SCRIPT_FILENAME']) !== 'post-new.php') {
            return;
        }

        $postType = !empty($_GET['post_type']) ? $_GET['post_type'] : 'post';
        if (!$postTypeObj = get_post_type_object($postType)) {
            return;
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
}