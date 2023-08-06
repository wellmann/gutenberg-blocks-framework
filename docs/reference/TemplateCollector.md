# TemplateCollector

Collects templates residing in the template directory.



* Full name: `\KWIO\GutenbergBlocks\TemplateCollector`


## Constants

| Name | Description |
|------|-------------|
| TEMPLATE_FOLDER | The template directory.  |

## Properties

| Name | Type | Description |
|------|------|-------------|
| config | `\KWIO\GutenbergBlocks\Config` | Holds the configurated options.  |

## Methods
### __construct 




```php
TemplateCollector::__construct(\KWIO\GutenbergBlocks\Config config)
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| config | `\KWIO\GutenbergBlocks\Config` | The configured options. |



### registerTemplates 
Registers the templates.



```php
TemplateCollector::registerTemplates(): void
```



**Returns:** `void` 

**See:**

* `\KWIO\GutenbergBlocks\Loader::int`  

### registerTemplate 
Registers a single template based on current post type.



```php
TemplateCollector::registerTemplate(string template): void
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| template | `string` | Template file for directory name. |


**Returns:** `void` 
### addNamespaceToBlockName 
Adds the namespace if block is part of current namespace.



```php
TemplateCollector::addNamespaceToBlockName(array template): array
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| template | `array` | Array of nested blocks. |


**Returns:** `array` Array of nested blocks with namespace.
