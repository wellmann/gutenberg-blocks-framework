<?php

namespace KWIO\GutenbergBlocksFramework\View;

use WP_Post;

abstract class AbstractView implements ViewInterface
{
    public string $defaultView = '';

    protected array $data = [];
    protected string $file = '';

    private string $baseClass = '';

     /**
     * Utility function for BEM style class names.
     */
    public function bem(string $element = '', array $modifiers = []): string
    {
        $elementClass = !empty($element) ? $this->baseClass . '__' . $element : $this->baseClass;
        $modifiers = array_map(fn($modifier) => $elementClass . '--' . $modifier, $modifiers);

        return $elementClass . (!empty($modifiers) ? ' ' . implode(' ', $modifiers) : '');
    }

    public function getPost(): WP_Post
    {
        global $post;

        return $post;
    }

    public function setData(array $data): ViewInterface
    {
        $this->baseClass = $data['baseClass'];
        unset($data['baseClass']);

        $this->data = $data;

        return $this;
    }

    public function setFile(string $file): ViewInterface
    {
        $this->file = $file;

        return $this;
    }
}