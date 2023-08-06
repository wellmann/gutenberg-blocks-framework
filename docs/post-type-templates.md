# Post Type Templates

The `KWIO\GutenbergBlocks\TemplateCollector` will look into the `post-type-templates` directory next to the blocks directory to find the template files.  
To create a default template for a post type you create a php file named after the post type slug (e.g. `page.php`).  
You can also create a folder named after the post type slug to group multiple templates which can then be selected by calling `new-post.php?template=my-template.php`.  
Blocks loaded from this library don't need their namespaces declared.


```php
<?php

return [
    'template' => [],
    'templateLock' => ''
];
```

For more see [developer.wordpress.org](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-templates/#custom-post-types)