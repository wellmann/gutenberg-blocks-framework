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
}