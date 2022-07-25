<?php

namespace KWIO\GutenbergBlocksFramework;

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
     * Holds the configurated options.
     *
     * @var PluginConfigDTO
     */
    protected PluginConfigDTO $pluginConfig;

    /**
     * Holds the HTML attributes that will be rendered on the block wrapper element.
     * Is reset on every render.
     *
     * @var array
     */
    protected array $tagAttr = [];

    /**
     * Hols name (e.g. `kwio/my-example`.) of current block.
     *
     * @var string
     */
    private string $blockName;

    /**
     * Holds attributes saved in the editor plus any other variables added in the block class.
     *
     * @var array
     */
    private array $data = [];

    /**
     * @ignore
     * @deprecated 1.1.0
     */
    private ?bool $hideMobile = null;

    /**
     * @ignore
     * @deprecated 1.1.0
     */
    private ?bool $hideDesktop = null;

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
     * @param string $blockName Block slug without namespace (e.g. `my-example`).
     * @param string $dirPath Path to current block.
     * @param PluginConfigDTO $pluginConfig Configurated options.
     */
    public function __construct(string $blockName, string $dirPath, PluginConfigDTO $pluginConfig)
    {
        $this->blockName = "{$pluginConfig->prefix}/{$blockName}";
        $this->baseClass = 'block-' . $blockName;
        $this->dirPath = trailingslashit($dirPath . $blockName);
        $this->pluginConfig = $pluginConfig;
        $this->viewClass = $pluginConfig->viewClass;
    }

    /**
     * Gets attributes of current block.
     *
     * @deprecated 1.1.0
     *
     * @return array JSON decoded attributes.
     */
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

    /**
     * Gets meta data (including attributes) for current block.
     * See [block.json](https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md) for more.
     *
     * @return array JSON decoded meta data.
     */
    public function getMetaData(): array
    {
        $metaJson = $this->dirPath . 'meta.json';
        if (!is_readable($metaJson)) {
            return [];
        }

        $metaData = file_get_contents($metaJson);
        if (!json_decode($metaData)) {
            return [];
        }

        return json_decode($metaData, true);
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

        $this->data = array_merge($this->data, $attributes, compact('content'));
        $this->hideMobile = $this->extractAttr('hideMobile');
        $this->hideDesktop = $this->extractAttr('hideDesktop');

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
                'prefix' => $this->pluginConfig->prefix,
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