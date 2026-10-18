<?php

namespace BlocksTestPlugin;

use KWIO\GutenbergBlocks\BaseBlock;
use WP_Block;

class Notice extends BaseBlock
{
    public function render(array $attributes, string $content, ?WP_Block $block = null): string
    {
        parent::render($attributes, $content, $block);

        $type = $attributes['type'] ?? 'info';
        $label = ucfirst($type);
        $this->addClass('%s--' . $type);

        return $this->setView($this->dirPath . 'view.php', compact('label'));
    }
}
