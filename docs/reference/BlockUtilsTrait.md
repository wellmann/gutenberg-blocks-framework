# BlockUtilsTrait

Set of utilitiy functions to be used inside the block class.



* Full name: `\KWIO\GutenbergBlocks\BlockUtilsTrait`




## Methods
### addClass 
Adds class to the block wrapper element.

Use `%s` as a placholder for the base class.

```php
BlockUtilsTrait::addClass(string class): void
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| class | `string` | Classname to add. |


**Returns:** `void` 
### addData 
Adds additional data via data attribute to the block wrapper element.



```php
BlockUtilsTrait::addData(string key, mixed value): void
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| key | `string` | Data identifier. |
| value | `mixed` | Data value. |


**Returns:** `void` 
### addInlineCss 
Adds inline CSS only when block is rendered.



```php
BlockUtilsTrait::addInlineCss(string cssFile): void
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| cssFile | `string` | Must be relative to the dist dir. |


**Returns:** `void` 
### addJsonData 
Adds JSON data via script tag after the opening block wrapper tag.



```php
BlockUtilsTrait::addJsonData(mixed jsonOrArray): void
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| jsonOrArray | `mixed` | JSON data to add. |


**Returns:** `void` 
### addJs 
Enqueues JS file only when block is rendered.

Most useful to enqueue a third party dependency of a rarely used block.

```php
BlockUtilsTrait::addJs(string jsFile, string dependentHandle = ''): void
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| jsFile | `string` | Must be relative to the dist dir. |
| dependentHandle | `string` | Handle of the script that needs this script loaded. |


**Returns:** `void` 
