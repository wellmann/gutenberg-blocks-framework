<?php

namespace KWIO\GutenbergBlocksFramework;

trait BlockUtilsTrait
{

    /**
     * Use '%s' as a placholder for the base class.
     */
    protected function addClass(string $class): void
    {
        $class = sprintf($class, $this->baseClass);
        $this->tagAttr['class'][] = sanitize_html_class($class);
    }

    /**
     * Add inline CSS only when block is rendered.
     *
     * @param string $cssFile - Must be relativ to the dist dir.
     */
    protected function addCss(string $cssFile): void
    {
        $cssFilePath = $this->pluginConfig->dirPath . $this->pluginConfig->distDir . $cssFile;
        if (!is_readable($cssFilePath)) {
            return;
        }

        $css = file_get_contents($cssFilePath);
        $css = str_replace('../../../../', content_url('/'), $css);

        static $isEnqueued = false;
        if (!$isEnqueued) {
            wp_register_style($this->baseClass, false);
            wp_enqueue_style($this->baseClass);
            wp_add_inline_style($this->baseClass, trim($css));

            $isEnqueued = true;
        }
    }

    /**
     * Add additional data via data attribute.
     *
     * @param string $key
     * @param mixed $value
     */
    protected function addData(string $key, $value): void
    {
        $key = sanitize_html_class($key);
        $value = is_scalar($value) ? $value : json_encode($value);

        $this->tagAttr['data-' . $key] = esc_attr($value);
    }

    /**
     * Enqueue JS file only when block is rendered.
     * Most useful to enqueue a third party dependency of a rarely used block.
     *
     * @param string $jsFile - Must be relativ to the dist dir.
     */
    protected function addJs(string $jsFile): void
    {
        $jsFilePath = $this->pluginConfig->dirPath . $this->pluginConfig->distDir . $jsFile;
        if (!is_readable($jsFilePath)) {
            return;
        }

        wp_enqueue_script(
            basename($jsFile, '.js'),
            $this->pluginConfig->dirUrl . $this->pluginConfig->distDir . $jsFile,
            [],
            substr(md5(filemtime($jsFilePath)), 0, 12),
            true
        );
    }
}