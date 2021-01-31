<?php

namespace KWIO\GutenbergBlocksFramework;

use KWIO\GutenbergBlocksFramework\View\ViewInterface;

class BaseBlock
{
    use BlockUtilsTrait;

    protected string $dirPath = '';
    protected string $baseClass = '';
    protected array $tagAttr = [];

    private array $data = [];
    private ?bool $hideMobile = null;
    private ?bool $hideDesktop = null;
    private int $renderCount = 0;
    private ViewInterface $viewClass;

    public function __construct(string $blockName, string $dirPath, ViewInterface $viewClass)
    {
        $this->baseClass = 'block-' . $blockName;
        $this->data['baseClass'] = $this->baseClass;
        $this->dirPath = trailingslashit($dirPath . $blockName);
        $this->viewClass = $viewClass;
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

        $this->data = array_merge($this->data, $attributes, compact('content'));
        $this->tagAttr = ['class' => ['block', $this->baseClass]]; // Reset for each render.
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
    protected function setView(?string $file, array $data = []): string
    {
        if (is_null($file)) {
            return '';
        }

        if (wp_is_mobile() && $this->hideMobile) {
            return '';
        }

        if (!wp_is_mobile() && $this->hideDesktop) {
            return '';
        }

        $this->tagAttr['class'] = $this->convertIsStyleToBem($this->tagAttr['class']);
        $tagAttrString = $this->buildTagAttrString($this->tagAttr);

        if (!file_exists($file)) {
            if (!empty($this->data['content'])) {
                return "<div{$tagAttrString}>{$this->data['content']}</div>";
            }

            return "<div{$tagAttrString}></div>";
        }

        $this->viewClass
            ->setData(array_merge($this->data, $data, ['renderCount' => $this->renderCount]))
            ->setFile($file);

        return "<div{$tagAttrString}>{$this->viewClass->render()}</div>";
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
        $this->tagAttr[$newAttr][] = $attr === 'align' && $value === 'full' ? 'alignfull' : $value;
        unset($this->data[$attr]);

        return $value;
    }

    /**
     * Workaround until https://github.com/WordPress/gutenberg/issues/11763 is fixed.
     */
    private function convertIsStyleToBem(array $classnames): array
    {
        return array_map(function (string $classname): string {
            return str_replace('is-style-', $this->baseClass . '--', $classname);
        }, $classnames);
    }

    /**
     * Convert key-value pairs to string of HTML attributes.
     */
    private function buildTagAttrString(array $array): string
    {
        $tagAttrString = '';
        foreach ($array as $key => $value) {
            if (empty($key)) {
                continue;
            }

            if (is_array($value)) {
                $value = implode(' ', $value);
            }

            $value = esc_attr($value);
            $tagAttrString .= " {$key}=\"{$value}\"";
        }

        return $tagAttrString;
    }
}