<?php

declare(strict_types=1);

namespace KWIO\GutenbergBlocks\View;

use KWIO\GutenbergBlocks\BaseBlock;
use WP_Post;

/**
 * Set of utilitiy functions to be used inside the view file.
 */
trait ViewUtilsTrait
{
    /**
     * Holds base classname of current block  (e.g. `block-my-example`).
     *
     * @var string
     */
    protected string $baseClass;

    /**
     * Holds how often the current view is rendered on the page.
     *
     * @var integer
     */
    protected int $renderCount;


    /**
     * Generates classnames according to the BEM methodology.
     *
     * @param string $element BEM element.
     * @param array $modifiers BEM modifier.
     *
     * @return string BEM classname.
     */
    public function bem(string $element = '', array $modifiers = []): string
    {
        $elementClass = !empty($element) ? $this->baseClass . '__' . $element : $this->baseClass;
        $modifiers = array_map(fn($modifier) => $elementClass . '--' . $modifier, $modifiers);

        return $elementClass . (!empty($modifiers) ? ' ' . implode(' ', $modifiers) : '');
    }

    /**
     * Checks if view is rendered in the block editor via `@wordpress/server-side-render`.
     *
     * @return boolean
     */
    public function isEditor(): bool
    {
        return defined('REST_REQUEST') && REST_REQUEST;
    }

    /**
     * Gets current `WP_Post` object.
     *
     * @return WP_Post
     */
    public function getPost(): WP_Post
    {
        global $post;

        return $post;
    }

    /**
     * Returns render count for current view.
     * If you have the same block multiple times on a page this function allows you to generate a unique class name or id.
     *
     * @return integer Render count for current view.
     */
    public function getRenderCount(): int
    {
        return $this->renderCount;
    }

    /**
     * Renders blocks loaded by this framework without specifing the namespace.
     *
     * @param string $blockName Block name without namespace or block class name.
     * @param array $attrs Block attributes.
     * @param string $content Block content.
     *
     * @return string Rendered block with attributes and content.
     */
    public function renderBlock(string $blockSlugOrClassName, array $attrs = [], string $content = ''): string
    {
        if (is_a($blockSlugOrClassName, BaseBlock::class, true)) {
            $blockSlugOrClassName = $blockSlugOrClassName::toSlug();
        }

        return render_block([
            'blockName' => $this->prefix . '/' . $blockSlugOrClassName,
            'attrs' => $attrs,
            'innerHTML' => $content
        ]);
    }
}