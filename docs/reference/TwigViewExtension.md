# TwigViewExtension





* Full name: `\KWIO\GutenbergBlocks\View\TwigViewExtension`



## Properties

| Name | Type | Description |
|------|------|-------------|
| twigView | `\KWIO\GutenbergBlocks\View\TwigView` | Holds instance of current view.  |

## Methods
### __construct 




```php
TwigViewExtension::__construct(\KWIO\GutenbergBlocks\View\TwigView twigView)
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| twigView | `\KWIO\GutenbergBlocks\View\TwigView` | Instance of current view. |



### getFunctions 
Returns a list of functions to add to the existing list.



```php
TwigViewExtension::getFunctions(): \Twig\TwigFunction[]
```



**Returns:** `\Twig\TwigFunction[]` 
