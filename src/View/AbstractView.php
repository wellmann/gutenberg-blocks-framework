<?php

declare(strict_types=1);

namespace KWIO\GutenbergBlocks\View;

abstract class AbstractView implements ViewInterface
{
    /**
     * Holds value of default view file name.
     *
     * @var string
     */
    public static string $defaultView;

    /**
     * Holds base classname of current block  (e.g. `block-my-example`).
     *
     * @var string
     */
    protected string $baseClass;

    /**
     * Holds absolute path to a custom cache directory for twig or blade views.
     *
     * @var string|null
     */
    protected ?string $cachePath = null;

    /**
     * The theme or plugin name.
     *
     * @var string
     */
    protected string $namespace;

    /**
     * Holds attributes saved in the editor plus any other variables added in the block class.
     *
     * @var array
     */
    protected array $data;

    /**
     * Holds absolute path to view file.
     *
     * @var string|null
     */
    protected ?string $file;

    /**
     * Sets the view files wrapper div element and attributes.
     *
     * @var string
     */
    private string $wrapperDiv;

    /**
     * Extract data variables for use in view file.
     *
     * @return string Rendered view fil with wrapper elment.
     */
    abstract protected function renderWithView(): string;

    public function setData(array $data): ViewInterface
    {
        $this->baseClass = $data['baseClass'];
        $this->namespace = $data['namespace'];
        $this->renderCount = $data['renderCount'];

        $tagAttrString = $this->buildTagAttrString($data['tagAttr']);
        $this->wrapperDiv = "<{$data['wrapperTagName']}{$tagAttrString}>{$data['afterOpeningTag']}###BLOCK_CONTENT###{$data['beforeClosingTag']}</{$data['wrapperTagName']}>";

        unset(
            $data['baseClass'],
            $data['afterOpeningTag'],
            $data['beforeClosingTag'],
            $data['namespace'],
            $data['renderCount'],
            $data['wrapperTagName'],
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

    public function setCachePath(?string $path): ViewInterface
    {
        $this->cachePath = $path;

        return $this;
    }

    public function render(): string
    {
        if (is_null($this->file)) {
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

    /**
     * Allows block view to be overridden in child theme.
     *
     * @param string $filePath Absolute path to view file.
     *
     * @return string Located view file.
     */
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
     * Converts key-value pairs to string of HTML attributes.
     * @see AbstractView::setData
     *
     * @param array $array Array of attributes.
     *
     * @return string String of HTML attributes.
     */
    private function buildTagAttrString(array $array): string
    {
        $tagAttrString = '';
        foreach ($array as $key => $value) {
            if (empty($key)) {
                continue;
            }

            if (is_array($value)) {
                $value = implode(' ', array_unique($value));
            }

            $value = esc_attr($value);
            $tagAttrString .= " {$key}=\"{$value}\"";
        }

        return $tagAttrString;
    }
}