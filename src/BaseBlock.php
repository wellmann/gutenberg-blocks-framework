<?php

declare(strict_types=1);

namespace KWIO\GutenbergBlocks;

use WP_Block;

/**
 * Default class for every custom block.
 */
class BaseBlock
{
    use BlockUtilsTrait;

    /**
     * Holds path to current block.
     *
     * @var string
     */
    protected string $dirPath = '';

    /**
     * Holds base classname of current block  (e.g. `block-my-example`).
     *
     * @var string
     */
    protected string $baseClass = '';

    /**
     * Holds name (e.g. `kwio/my-example`.) of current block.
     *
     * @var string
     */
    protected string $name;

    /**
     * Holds slug (e.g. `my-example`.) of current block.
     *
     * @var string
     */
    protected string $slug;

    /**
     * Holds the configurated options.
     *
     * @var Config
     */
    protected Config $config;

    /**
     * Holds the HTML attributes that will be rendered on the block wrapper element.
     * Is reset on every render.
     *
     * @var array
     */
    protected array $tagAttr = [];

    /**
     * Holds attributes saved in the editor plus any other variables added in the block class.
     *
     * @var array
     */
    private array $data = [];

    /**
     * Holds how often the current block is rendered on the page.
     *
     * @var integer
     */
    private int $renderCount = 0;

    /**
     * Holds current view implementaion.
     * @see AbstractView
     *
     * @var string
     */
    private string $viewClass;

    /**
     * Creates instance of current block type once per request.
     *
     * @param string $slug Block slug without namespace (e.g. `my-example`).
     * @param string $dirPath Path to current block.
     * @param Config $config Configurated options.
     */
    public function __construct(string $slug, string $dirPath, Config $config)
    {
        $this->slug = $slug;
        $this->name = "{$config->namespace}/{$slug}";
        $this->baseClass = 'block-' . $slug;
        $this->dirPath = trailingslashit($dirPath . $slug);
        $this->config = $config;
        $this->viewClass = $config->viewClass;
    }

    /**
     * Returns render count for current block type.
     *
     * @return integer Render count for current block.
     */
    public function getRenderCount(): int
    {
        return $this->renderCount;
    }

    /**
     * Render callback passed to `register_block_type`.
     *
     * @param array $attributes Holds attributes saved in the editor.
     * @param string $content Holds content saved in the editor.
     * @param WP_Block|null $block Holds additional block information like context.
     *
     * @return string Rendered HTML output of current block.
     */
    public function render(array $attributes, string $content, ?WP_Block $block = null): string
    {
        $this->renderCount++;

        // Reset for each render.
        $this->data = [
            'baseClass' => $this->baseClass,
            'afterOpeningTag' => '',
            'beforeClosingTag' => '',
        ];
        $this->tagAttr = ['class' => ['block', $this->baseClass]];

        if (!empty($attributes['backgroundColor'])) {
            $this->tagAttr['class'][] = 'has-background';
            $this->tagAttr['class'][] = "has-{$attributes['backgroundColor']}-background-color";
        }

        if (!empty($attributes['textColor'])) {
            $this->tagAttr['class'][] = 'has-text-color';
            $this->tagAttr['class'][] = "has-{$attributes['textColor']}-color";
        }

        $this->data = array_merge($this->data, $attributes, compact('content'));

        $this->extractAttr('className', 'class');
        $this->extractAttr('align', 'class');
        $this->extractAttr('anchor', 'id');

        return $this->setView($this->dirPath . $this->viewClass::$defaultView);
    }

    /**
     * Renders current block.
     *
     * @param string|null $file Absolute path to current blocks view file.
     * @param array $data Attributes saved in the editor plus any other variables added in the block class.
     * @param string $wrapperTagName Block wrapper element tag name.
     *
     * @return string Rendered HTML output of current block.
     */
    protected function setView(?string $file, array $data = [], $wrapperTagName = 'div'): string
    {
        $viewClassInstance = new $this->viewClass();
        return $viewClassInstance
            ->setData(array_merge($this->data, $data, [
                'namespace' => $this->config->namespace,
                'renderCount' => $this->renderCount,
                'wrapperTagName' => $wrapperTagName,
                'tagAttr' => $this->tagAttr
            ]))
            ->setFile($file)
            ->setCachePath($this->config->viewCachePath)
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

    /**
     * Convert KWIO\GutenbergBlocks\MyExample::class to my-example
     *
     * @return string
     */
    public static function toSlug(): string
    {
        $blockClassParts = explode('\\', static::class);
        $blockClass = array_pop($blockClassParts);
        $slug = preg_replace('%([a-z])([A-Z])%', '$1-$2', $blockClass);
        $slug = strtolower($slug);

        return $slug;
    }
}