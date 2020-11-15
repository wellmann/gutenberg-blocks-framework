<?php

namespace KWIO\GutenbergBlocksFramework;

use WP_Post;

class BlockView
{

    protected $baseClass;
    protected $data;

    public function __construct(array $data)
    {
        $this->baseClass = $data['baseClass'];
        unset($data['baseClass']);

        $this->data = $data;
    }

    public function load(string $viewFile): string
    {
        ob_start();
        extract($this->data, EXTR_SKIP);
        unset($this->data);
        include $viewFile;

        return ob_get_clean();
    }

    public function getPost(): WP_Post
    {
        global $post;

        return $post;
    }

    /**
     * Utility function for BEM style class names.
     */
    public function bem(string $element = '', array $modifiers = []): string
    {
        $elementClass = !empty($element) ? $this->baseClass . '__' . $element : $this->baseClass;
        $modifiers = array_map(function ($modifier) use ($elementClass) {
            return $elementClass . '--' . $modifier;
        }, $modifiers);

        return $elementClass . (!empty($modifiers) ? ' ' . implode(' ', $modifiers) : '');
    }
}