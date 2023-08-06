# ViewUtilsTrait

Set of utilitiy functions to be used inside the view file.



* Full name: `\KWIO\GutenbergBlocks\View\ViewUtilsTrait`



## Properties

| Name | Type | Description |
|------|------|-------------|
| baseClass | `string` | Holds base classname of current block  (e.g. `block-my-example`).  |
| renderCount | `int` | Holds how often the current view is rendered on the page.  |

## Methods
### bem 
Generates classnames according to the BEM methodology.



```php
ViewUtilsTrait::bem(string element = '', array modifiers = [], bool jsPrefix = false): string
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| element | `string` | BEM element. |
| modifiers | `array` | BEM modifier. |
| jsPrefix | `bool` |  |


**Returns:** `string` BEM classname.
### isEditor 
Checks if view is rendered in the block editor via `@wordpress/server-side-render`.



```php
ViewUtilsTrait::isEditor(): bool
```



**Returns:** `bool` 
### getPost 
Gets current `WP_Post` object.



```php
ViewUtilsTrait::getPost(): \WP_Post
```



**Returns:** `\WP_Post` 
### getRenderCount 
Returns render count for current view.

If you have the same block multiple times on a page this function allows you to generate a unique class name or id.

```php
ViewUtilsTrait::getRenderCount(): int
```



**Returns:** `int` Render count for current view.
### renderBlock 
Renders blocks loaded by this framework without specifing the namespace.



```php
ViewUtilsTrait::renderBlock(string blockSlugOrClassName, array attrs = [], string content = ''): string
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| blockSlugOrClassName | `string` | Block name without namespace or block class name. |
| attrs | `array` | Block attributes. |
| content | `string` | Block content. |


**Returns:** `string` Rendered block with attributes and content.
