<?php

declare(strict_types=1);

namespace KWIO\GutenbergBlocks\Attribute;

use Attribute;

/**
 * Post type visibility attribute.
 * @see BaseBlock::class
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Visibility
{
    public array $postTypes;

    public function __construct(array $postTypes)
    {
        $this->postTypes = $postTypes;
    }
}