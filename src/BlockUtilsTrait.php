<?php

namespace KWIO\GutenbergBlocksFramework;

/**
 * Set of utilitiy functions to be used inside the block class.
 */
trait BlockUtilsTrait
{
    /**
     * @ignore
     */
    private bool $hasChild = false;

    /**
     * @ignore
     */
    private bool $hasParent = false;

    /**
     * Adds class to the block wrapper element.
     * Use `%s` as a placholder for the base class.
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
     * Adds inline CSS only when block is rendered.
     *
     * @param string $cssFile Must be relative to the dist dir.
     */
    protected function addInlineCss(string $cssFile): void
    {
        $cssFilePath = $this->pluginConfig->dirPath . $this->pluginConfig->distDir . $cssFile;
        if (!is_readable($cssFilePath)) {
            return;
        }

        $criticalCss = file_get_contents($cssFilePath);
        $criticalCss = str_replace('../../../../', content_url('/'), $criticalCss);

        if(wp_style_is($this->baseClass)) {
            return;
        }

        wp_register_style($this->baseClass, false);
        wp_enqueue_style($this->baseClass);
        wp_add_inline_style($this->baseClass, trim($criticalCss));
    }

    /**
     * Adds JSON data via script tag after the opening block wrapper tag.
     *
     * @param mixed $jsonOrArray JSON data to add.
     */
    protected function addJsonData($jsonOrArray): void
    {
        $json = is_scalar($jsonOrArray) ? $jsonOrArray : json_encode($jsonOrArray);

        $this->data['afterOpeningTag'] = sprintf('<script type="application/json">%s</script>', $json) . "\n";
    }

    /**
     * Enqueues JS file only when block is rendered.
     * Most useful to enqueue a third party dependency of a rarely used block.
     *
     * @param string $jsFile Must be relative to the dist dir.
     * @param string $dependentHandle Handle of the script that needs this script loaded.
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

    /**
     * @ignore
     */
    public function hasChild(string $childBlockName = ''): bool
    {
        return $this->hasRelative('child', $childBlockName);
    }

    /**
     * @ignore
     */
    public function hasChildren(): bool
    {
        return $this->hasChild();
    }

    /**
     * @ignore
     */
    public function hasParent(string $parentBlockName = ''): bool
    {
        return $this->hasRelative('parent', $parentBlockName);
    }

    /**
     * @ignore
     */
    private function hasRelative(string $type, string $blockName = ''): bool
    {
        if (strpos($this->blockName, $this->pluginConfig->prefix . '/core-') === 0) {
            $this->blockName = 'core/' . preg_replace("/^{$this->pluginConfig->prefix}\/core-/", '', $this->blockName);
        }

        add_filter('render_block_data', function ($parsedBlock) use ($type, $blockName) {
            if (empty($parsedBlock['innerBlocks'])) {
                $parsedBlock['innerBlocks'] = [];
            }

            // Reset for each render.
            $this->hasChild = false;
            $this->hasParent = false;

            array_walk($parsedBlock['innerBlocks'], fn(&$item, $key) => $this->hasRelativeCheck($item, $key, $type, $blockName));

            return $parsedBlock;
        });

        switch ($type) {
            case 'child':
                return $this->hasChild;
            case 'parent':
                return $this->hasParent;
            default:
                return false;
        }
    }

    /**
     * @ignore
     */
    private function hasRelativeCheck(&$item, $key, $type, $blockName)
    {
        if (empty($item['innerBlocks'])) {
            return;
        }

        $this->hasChild = true;

        if ($type === 'child' && !empty($item['blockName']) && $item['blockName'] === $this->blockName) {
            $this->hasChild = $this->isInInnerBlocks($blockName, $item);

            return;
        }

        if ($type === 'parent' && empty($blockName)) {
            $this->hasParent = true;

            return;
        }


        if ($type === 'parent' && !empty($item['blockName']) && $item['blockName'] === $blockName) {
            $this->hasParent = $this->isInInnerBlocks($this->blockName, $item);
        }

        array_walk($item['innerBlocks'], fn(&$item, $key) => $this->hasRelativeCheck($item, $key, $type, $blockName));
    }

    /**
     * @ignore
     */
    private function isInInnerBlocks(string $blockName, array $item): bool
    {
        return array_search($blockName, array_column($item['innerBlocks'], 'blockName')) !== false;
    }
}