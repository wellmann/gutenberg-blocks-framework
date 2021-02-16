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
     * @param string $jsFile - Must be relative to the dist dir.
     * @param string $dependentHandle - Handle of the script that needs this script loaded.
     */
    protected function addJs(string $jsFile, string $dependentHandle = ''): void
    {
        $jsFilePath = $this->pluginConfig->dirPath . $this->pluginConfig->distDir . $jsFile;
        if (!is_readable($jsFilePath)) {
            return;
        }

        $handle = basename($jsFile, '.js');

        if (!empty($dependentHandle)) {
            $dependent = $GLOBALS['wp_scripts']->query($dependentHandle, 'registered');
            if (!$dependent) {
                return;
            }

            $dependent->deps[] = $handle;
        }

        wp_enqueue_script(
            $handle,
            $this->pluginConfig->dirUrl . $this->pluginConfig->distDir . $jsFile,
            [],
            substr(md5(filemtime($jsFilePath)), 0, 12),
            true
        );
    }
}