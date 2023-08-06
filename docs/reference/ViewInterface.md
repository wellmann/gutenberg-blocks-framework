# ViewInterface





* Full name: `\KWIO\GutenbergBlocks\View\ViewInterface`




## Methods
### render 
Renders view with data.



```php
ViewInterface::render(): string
```



**Returns:** `string` Rendered view.
### setData 
Adds data to view.



```php
ViewInterface::setData(array data): \KWIO\GutenbergBlocks\View\ViewInterface
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| data | `array` | Data for view. |


**Returns:** `\KWIO\GutenbergBlocks\View\ViewInterface` 
### setFile 
Sets view file to populate with data.



```php
ViewInterface::setFile(string file): \KWIO\GutenbergBlocks\View\ViewInterface
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| file | `string` | Absolute path to view file. |


**Returns:** `\KWIO\GutenbergBlocks\View\ViewInterface` 
