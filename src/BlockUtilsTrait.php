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
     * Adds CSS only when the rendered block has a class matching the file name.
     *
     * @param string $cssFile Must be relative to the dist dir; its basename (without extension) must match a block class.
     */
    protected function addCssForClass(string $cssFile): void
    {
        $className = basename($cssFile, '.css');

        $this->addCss(
            $cssFile,
            fn () => in_array($className, $this->tagAttr['class'] ?? [], true)
        );
    }

    /**
     * Adds block-specific CSS only when rendered.
     *
     * @param string $cssFile Must be relative to the dist dir.
     * @param callable|null $condition Condition to check before adding the style.
     */
    protected function addCss(string $cssFile, ?callable $condition = null): void
    {
        $cssFilePath = $this->config->dirPath . $this->config->distDir . $cssFile;
        if (!is_readable($cssFilePath)) {
            return;
        }

        if ($condition && !call_user_func($condition)) {
            return;
        }

        $name = str_starts_with($this->slug, 'core-') ? 'core/' . preg_replace('/^core-/', '', $this->slug) : $this->name;

        wp_enqueue_block_style($name, [
            'handle' => "{$this->config->namespace}-{$this->slug}-" . basename($cssFilePath, '.css'),
            'src' => $this->config->dirUrl . $this->config->distDir . $cssFile,
            'path' => $cssFilePath,
            'ver' => filemtime($cssFilePath),
        ]);
    }

    /**
     * Adds JS only when the rendered block has a class matching the file name.
     *
     * @param string $jsFile Must be relative to the dist dir; its basename (without extension) must match a block class.
     */
    protected function addJsForClass(string $jsFile): void
    {
        $className = basename($jsFile, '.js');

        $this->addJs(
            $jsFile,
            '',
            fn () => in_array($className, $this->tagAttr['class'] ?? [], true)
        );
    }

    /**
     * Adds block-specific JS only when rendered.
     *
     * @param string $jsFile Must be relative to the dist dir.
     * @param string $dependentHandle Handle of the script that requires this script.
     * @param callable|null $condition Condition to check before adding the script.
     */
    protected function addJs(string $jsFile, string $dependentHandle = '', ?callable $condition = null): void
    {
        $jsFilePath = $this->config->dirPath . $this->config->distDir . $jsFile;
        if (!is_readable($jsFilePath)) {
            return;
        }

        if (!empty($condition) && !call_user_func($condition)) {
            return;
        }

        $handle = "{$this->config->namespace}-{$this->slug}-" . basename($jsFilePath, '.js');

        if (!empty($dependentHandle)) {
            $dependent = $GLOBALS['wp_scripts']->query($dependentHandle, 'registered');
            if (!$dependent) {
                return;
            }

            $dependent->deps[] = $handle;
        }

        wp_enqueue_script(
            $handle,
            $this->config->dirUrl . $this->config->distDir . $jsFile,
            [],
            filemtime($jsFilePath),
            [
                'strategy' => 'defer',
                'in_footer' => true,
            ]
        );
    }
}