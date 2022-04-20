<?php

namespace KWIO\GutenbergBlocksFramework\View;

abstract class AbstractView implements ViewInterface
{
    public static string $defaultView;

    protected string $baseClass;
    protected string $prefix;
    protected array $data;
    protected ?string $file;

    private string $wrapperDiv;
    private ?bool $hideMobile;
    private ?bool $hideDesktop;

    abstract protected function renderWithView(): string;

    public function setData(array $data): ViewInterface
    {
        $this->baseClass = $data['baseClass'];
        $this->prefix = $data['prefix'];
        $this->renderCount = $data['renderCount'];
        $this->hideMobile = $data['hideMobile'];
        $this->hideDesktop = $data['hideDesktop'];

        $data['tagAttr']['class'] = $this->convertIsStyleToBem($data['tagAttr']['class']);
        $tagAttrString = $this->buildTagAttrString($data['tagAttr']);
        $this->wrapperDiv = "<{$data['wrapperTagName']}{$tagAttrString}>{$data['afterOpeningTag']}###BLOCK_CONTENT###{$data['beforeClosingTag']}</{$data['wrapperTagName']}>";

        unset(
            $data['baseClass'],
            $data['afterOpeningTag'],
            $data['beforeClosingTag'],
            $data['prefix'],
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

    public function render(): string
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
                return $isCoreBlock ? $this->data['content'] : str_replace('###BLOCK_CONTENT###', $this->data['content'], $this->wrapperDiv);
            }

            return str_replace('###BLOCK_CONTENT###', '', $this->wrapperDiv);
        }

        // Don't render custom wrapper for overridden core block.
        return $isCoreBlock ? $this->renderWithView() : str_replace('###BLOCK_CONTENT###', $this->renderWithView(), $this->wrapperDiv);
    }

    protected function locateView(string $filePath): string
    {
        // If blocks are located in the theme make the view file overridable in child theme.
        if (strpos($this->file, '/themes/') !== false) {
            $relativeFilePath = str_replace([STYLESHEETPATH, TEMPLATEPATH], '', $filePath);

            if (file_exists(STYLESHEETPATH . '/' . $relativeFilePath)) {
                $filePath = STYLESHEETPATH . '/' . $relativeFilePath;
            } elseif (file_exists(TEMPLATEPATH . '/' . $relativeFilePath)) {
                $filePath = TEMPLATEPATH . '/' . $relativeFilePath;
            }
        }

        return $filePath;
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