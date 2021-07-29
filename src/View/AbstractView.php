<?php

namespace KWIO\GutenbergBlocksFramework\View;

abstract class AbstractView implements ViewInterface
{
    public string $defaultView;

    protected string $baseClass;
    protected array $data;
    protected ?string $file;

    private string $wrapperDiv;
    private ?bool $hideMobile;
    private ?bool $hideDesktop;

    abstract public function render(): string;

    public function setData(array $data): ViewInterface
    {
        $this->baseClass = $data['baseClass'];
        $this->renderCount = $data['renderCount'];
        $this->hideMobile = $data['hideMobile'];
        $this->hideDesktop = $data['hideDesktop'];

        $data['tagAttr']['class'] = $this->convertIsStyleToBem($data['tagAttr']['class']);
        $tagAttrString = $this->buildTagAttrString($data['tagAttr']);
        $this->wrapperDiv = "<{$data['wrapperTagName']}{$tagAttrString}>%s</{$data['wrapperTagName']}>";

        unset(
            $data['baseClass'],
            $data['renderCount'],
            $data['wrapperTagName'],
            $data['hideMobile'],
            $data['hideDesktop'],
            $data['tagAttr']
        );

        $this->data = $data;

        return $this;
    }

    public function setFile(?string $file): ViewInterface
    {
        $this->file = $file;

        return $this;
    }

    protected function wrap(string $renderedView): string
    {
        if (is_null($this->file)) {
            return '';
        }

        if (wp_is_mobile() && $this->hideMobile) {
            return '';
        }

        if (!wp_is_mobile() && $this->hideDesktop) {
            return '';
        }

        $isCoreBlock = strpos($this->baseClass, 'block-core-') === 0;

        if (!file_exists($this->file)) {
            if (!empty($this->data['content'])) {

                // Don't render custom wrapper for overridden core block.
                return $isCoreBlock ? $this->data['content'] : sprintf($this->wrapperDiv, $this->data['content']);
            }

            return sprintf($this->wrapperDiv, '');
        }

        // Don't render custom wrapper for overridden core block.
        return $isCoreBlock ? $renderedView : sprintf($this->wrapperDiv, $renderedView);
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