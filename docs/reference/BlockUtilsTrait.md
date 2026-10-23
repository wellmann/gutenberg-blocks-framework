# BlockUtilsTrait

Set of utility functions to be used inside the block class.



* Full name: `\KWIO\GutenbergBlocks\BlockUtilsTrait`




## Methods
### addClass 
Adds class to the block wrapper element.

Use `%s` as a placeholder for the base class.

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
### addCssForClass 
Adds CSS only when rendered and the block wrapper has a class matching the filename (without extension). This can target pattern, block style, or variation classes.



```php
BlockUtilsTrait::addCssForClass(string cssFile): void
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| cssFile | `string` | Must be relative to the dist dir and match a block class. |


**Returns:** `void` 
### addCss 
Adds block-specific CSS only when rendered.



```php
BlockUtilsTrait::addCss(string cssFile, callable|null condition = null): void
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| cssFile | `string` | Must be relative to the dist dir. |
| condition | `callable\|null` | Condition to check before adding the style. |


**Returns:** `void` 
### addJsForClass 
Adds JS only when rendered and the block wrapper has a class matching the filename (without extension). This can target pattern, block style, or variation classes.



```php
BlockUtilsTrait::addJsForClass(string jsFile): void
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| jsFile | `string` | Must be relative to the dist dir and match a block class. |


**Returns:** `void` 
### addJs 
Adds block-specific JS only when rendered.



```php
BlockUtilsTrait::addJs(string jsFile, string dependentHandle = '', callable|null condition = null): void
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| jsFile | `string` | Must be relative to the dist dir. |
| dependentHandle | `string` | Handle of the script that requires this script. |
| condition | `callable\|null` | Condition to check before adding the script. |


**Returns:** `void` 
