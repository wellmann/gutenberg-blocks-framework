# BlockCollector

Collects the blocks residing in the blocks directory.



* Full name: `\KWIO\GutenbergBlocks\BlockCollector`



## Properties

| Name | Type | Description |
|------|------|-------------|
| blocks | `array` | Holds all successfully registered blocks.  |
| blockDirPath | `string` | Holds the path to the blocks directoy.  |
| config | `\KWIO\GutenbergBlocks\Config` | Holds the configurated options.  |
| restrictedBlocks | `array` | Holds blocks that are restricted to speciffic post types.  |

## Methods
### __construct 




```php
BlockCollector::__construct(\KWIO\GutenbergBlocks\Config config)
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| config | `\KWIO\GutenbergBlocks\Config` | The configured options. |



### filterBlocks 
Removes blocks from block selector if they are not elligible for display on current post type.



```php
BlockCollector::filterBlocks(bool|array allowedBlockTypes, \WP_Block_Editor_Context blockEditorContext): mixed
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| allowedBlockTypes | `bool\|array` | Array of block type slugs, or boolean to enable/disable all. Default true (all registered block types supported). |
| blockEditorContext | `\WP_Block_Editor_Context` | The current block editor context. |


**Returns:** `mixed` 

**See:**

* `\KWIO\GutenbergBlocks\Loader::int`  

### registerBlocks 
Registers the blocks.



```php
BlockCollector::registerBlocks(): void
```



**Returns:** `void` 

**See:**

* `\KWIO\GutenbergBlocks\Loader::int`  

### registerBlock 
Registers a single block.



```php
BlockCollector::registerBlock(string block): void
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| block | `string` | Block name without namespace. |


**Returns:** `void` 

**See:**

* `\KWIO\GutenbergBlocks\BlockCollector::registerBlocks`  

