<?php

namespace KWIO\GutenbergBlocksFramework\View;

use WP_Post;

trait ViewUtilsTrait
{
    protected string $baseClass;
    protected int $renderCount;

    /**
     * Utility function for BEM style class names.
     */
    public function bem(string $element = '', array $modifiers = []): string
    {
        $elementClass = !empty($element) ? $this->baseClass . '__' . $element : $this->baseClass;
        $modifiers = array_map(fn($modifier) => $elementClass . '--' . $modifier, $modifiers);

        return $elementClass . (!empty($modifiers) ? ' ' . implode(' ', $modifiers) : '');
    }

    public function isEditor(): bool
    {
        return defined('REST_REQUEST') && REST_REQUEST;
    }

    public function getPost(): WP_Post
    {
        global $post;

        return $post;
    }

    public function getRenderCount(): int
    {
        return $this->renderCount;
    }

    public function renderBlock(string $blockFullClassName, array $attrs = [], string $content = ''): string
    {
        // Convert KWIO\GutenbergBlocks\MyExample to my-example
        $blockClassParts = explode('\\', $blockFullClassName);
        $blockClass = array_pop($blockClassParts);
        $blockName = preg_replace('%([a-z])([A-Z])%', '$1-$2', $blockClass);

        return $this->_renderBlockInternal($blockName, $attrs, $content);
    }

    public function _renderBlockInternal(string $blockName,  array $attrs = [], string $content = ''): string
    {
        return render_block([
            'blockName' => $this->prefix . '/' . $blockName,
            'attrs' => $attrs,
            'innerHTML' => $content
        ]);
    }
}