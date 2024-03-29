# Block Class

If your block doesn't require any PHP logic you don't have to create this file since the `KWIO\GutenbergBlocks\BaseBlock` class will be used by default.

```php
<?php

namespace KWIO\GutenbergBlocks;

use KWIO\GutenbergBlocks\BaseBlock;
use WP_Block;

class MyBlock extends BaseBlock
{
    public function render(array $attributes, string $content, ?WP_Block $block = null): string
    {
        parent::render($attributes, $content);

        $customAttr = ... // Your custom logic here

        return $this->setView($this->dirPath . 'view.php', compact('customAttr'));
    }
}
```

[BaseBlock class reference](reference/BaseBlock)


## Changing the visibility

To change the visibility of a specific block type add the `SHOW_ON_POST_TYPE` constant to your block class and pass an array of post type slugs to which this block should be visible only.

```php
<?php

namespace KWIO\GutenbergBlocks;

use KWIO\GutenbergBlocks\BaseBlock;

class MyBlock extends BaseBlock
{
  const SHOW_ON_POST_TYPE = ['page'];
}
```

If you are using PHP 8 you can also use the `KWIO\GutenbergBlocks\Attribute\Visibility` attribute.
```php
<?php

namespace KWIO\GutenbergBlocks;

use KWIO\GutenbergBlocks\Attribute\Visibility;
use KWIO\GutenbergBlocks\BaseBlock;

#[Visibility(postTypes: ['post'])]
class MyBlock extends BaseBlock
{
  ...
}
```

## Utility methods

[BlockUtilsTrait reference](reference/BlockUtilsTrait)