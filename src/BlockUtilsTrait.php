<?php

declare(strict_types=1);

namespace KWIO\GutenbergBlocks;

/**
 * Set of utility functions to be used inside the block class.
 */
trait BlockUtilsTrait
{
    /**
     * Adds class to the block wrapper element.
     * Use `%s` as a placeholder for the base class.
     *
     * @param string $class Classname to add.
     */
    protected function addClass(string $class): void
    {
        $class = sprintf($class, $this->baseClass);
        $this->tagAttr['class'][] = sanitize_html_class($class);
    }

    /**
     * Adds additional data via data attribute to the block wrapper element.
     *
     * @param string $key Data identifier.
     * @param mixed $value Data value.
     */
    protected function addData(string $key, $value): void
    {
        $key = sanitize_html_class($key);
        $value = is_scalar($value) ? $value : json_encode($value);

        $this->tagAttr['data-' . $key] = esc_attr($value);
    }

    /**
     * Adds inline CSS only when the block is rendered.
     *
     * @param string $cssFile Must be relative to the dist dir.
     */
    protected function addInlineCss(string $cssFile): void
    {
        if (wp_style_is($this->baseClass)) {
            return;
        }

        $cssFilePath = $this->config->dirPath . $this->config->distDir . $cssFile;
        if (!is_readable($cssFilePath)) {
            return;
        }

        $criticalCss = file_get_contents($cssFilePath);
        $criticalCss = str_replace('../../../../', content_url('/'), $criticalCss);

        wp_register_style($this->baseClass, false);
        wp_enqueue_style($this->baseClass);
        wp_add_inline_style($this->baseClass, trim($criticalCss));
    }

    /**
     * Adds JSON data via script tag after the opening block wrapper tag.
     *
     * @param mixed $jsonOrArray JSON data to add.
     */
    protected function addJsonData(mixed $jsonOrArray): void
    {
        $json = is_scalar($jsonOrArray) ? $jsonOrArray : json_encode($jsonOrArray);

        $this->data['afterOpeningTag'] = sprintf('<script type="application/json">%s</script>', $json) . "\n";
    }

    /**
     * Adds pattern-specific CSS only when rendered.
     *
     * @param string $cssFile Must be relative to the dist dir.
     * @param string $className Class name of the pattern.
     */
    protected function addPatternCss(string $cssFile, string $className): void
    {
        if (!in_array($className, $this->tagAttr['class'] ?? [], true)) {
            return;
        }

        $this->addCss($cssFile,"{$this->config->namespace}-{$this->slug}-$className");
    }

    /**
     * Adds block-specific CSS only when rendered.
     *
     * @param string $cssFile
     * @param string|null $handle
     */
    protected function addCss(string $cssFile, ?string $handle = null): void
    {
        $cssFilePath = $this->config->dirPath . $this->config->distDir . $cssFile;
        if (!is_readable($cssFilePath)) {
            return;
        }

        $name = str_starts_with($this->slug, 'core-') ? 'core/' . preg_replace('/^core-/', '', $this->slug) : $this->name;
        $filename = basename($cssFilePath, '.css');
        $handle = $handle ?? "{$this->config->namespace}-{$this->slug}-$filename";

        wp_enqueue_block_style($name, [
            'handle' => $handle,
            'src' => $this->config->dirUrl . $this->config->distDir . $filename,
            'path' => $cssFilePath,
            'ver' => filemtime($cssFilePath),
        ]);
    }
}