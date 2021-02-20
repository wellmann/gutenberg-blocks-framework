<?php

namespace KWIO\GutenbergBlocksFramework\View;

use WP_Post;

abstract class AbstractView implements ViewInterface
{
    public string $defaultView = '';

    protected array $data = [];
    protected string $file = '';

    private string $baseClass = '';
    private int $renderCount = 0;

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

    public function setData(array $data): ViewInterface
    {
        $this->baseClass = $data['baseClass'];
        $this->renderCount = $data['renderCount'];
        unset($data['baseClass'], $data['renderCount']);

        $this->data = $data;

        return $this;
    }

    public function setFile(string $file): ViewInterface
    {
        $this->file = $file;

        return $this;
    }
}