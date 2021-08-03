<?php

namespace KWIO\GutenbergBlocksFramework;

trait BlockUtilsTrait
{
    private bool $hasChild = false;
    private bool $hasParent = false;

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

    public function hasChild(string $childBlockName = ''): bool
    {
        return $this->hasRelative('child', $childBlockName);
    }

    public function hasChildren(): bool
    {
        return $this->hasChild();
    }

    public function hasParent(string $parentBlockName = ''): bool
    {
        return $this->hasRelative('parent', $parentBlockName);
    }

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

    private function isInInnerBlocks(string $blockName, array $item): bool
    {
        return array_search($blockName, array_column($item['innerBlocks'], 'blockName')) !== false;
    }
}