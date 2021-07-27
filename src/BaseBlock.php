<?php

namespace KWIO\GutenbergBlocksFramework;

use KWIO\GutenbergBlocksFramework\View\ViewInterface;

class BaseBlock
{
    use BlockUtilsTrait;

    protected string $dirPath = '';
    protected string $baseClass = '';
    protected PluginConfigDTO $pluginConfig;
    protected array $tagAttr = [];

    private string $blockName;
    private array $data = [];
    private ?bool $hideMobile = null;
    private ?bool $hideDesktop = null;
    private int $renderCount = 0;
    private ViewInterface $viewClass;

    public function __construct(string $blockName, string $dirPath, PluginConfigDTO $pluginConfig)
    {
        $this->blockName = "{$pluginConfig->prefix}/{$blockName}";
        $this->baseClass = 'block-' . $blockName;
        $this->dirPath = trailingslashit($dirPath . $blockName);
        $this->pluginConfig = $pluginConfig;
        $this->viewClass = $pluginConfig->viewClass;
    }

    public function getAttributes(): array
    {
        $attributesJson = $this->dirPath . 'attributes.json';
        if (!is_readable($attributesJson)) {
            return [];
        }

        $attributes = file_get_contents($attributesJson);
        if (!json_decode($attributes)) {
            return [];
        }

        return json_decode($attributes, true);
    }

    public function getRenderCount(): int
    {
        return $this->renderCount;
    }

    public function render(array $attributes, string $content): string
    {
        $this->renderCount++;

        // Reset for each render.
        $this->data = ['baseClass' => $this->baseClass];
        $this->tagAttr = ['class' => ['block', $this->baseClass]];

        $this->data = array_merge($this->data, $attributes, compact('content'));
        $this->hideMobile = $this->extractAttr('hideMobile');
        $this->hideDesktop = $this->extractAttr('hideDesktop');

        $this->extractAttr('className', 'class');
        $this->extractAttr('align', 'class');
        $this->extractAttr('anchor', 'id');

        return $this->setView($this->dirPath . $this->viewClass->defaultView);
    }

    /**
     * Rendered HTML output of the block.
     */
    protected function setView(?string $file, array $data = [], $wrapperTagName = 'div'): string
    {
        return $this->viewClass
            ->setData(array_merge($this->data, $data, [
                'renderCount' => $this->renderCount,
                'wrapperTagName' => $wrapperTagName,
                'hideMobile' => $this->hideMobile,
                'hideDesktop' => $this->hideDesktop,
                'tagAttr' => $this->tagAttr
            ]))
            ->setFile($file)
            ->render();
    }

    /**
     * Extract key–value pair from data passed to the blocks view
     * and optionally rename it for use as HTML attribute.
     *
     * @param string $attr Attribute name to extract.
     * @param string $newAttr New attribute name to extract value into.
     *
     * @return null|string Attribute value.
     */
    private function extractAttr(string $attr, string $newAttr = ''): ?string
    {
        if (!array_key_exists($attr, $this->data)) {
            return null;
        }

        if (empty($this->data[$attr])) {
            return null;
        }

        $value = $this->data[$attr];
        $this->tagAttr[$newAttr][] = $attr === 'align' &&  in_array($value, ['full', 'wide']) ? 'align' . $value : $value;
        unset($this->data[$attr]);

        return $value;
    }
}