---
layout: default
title: TwigViewExtension
parent: Reference
has_toc: false
---

# TwigViewExtension
{: .no_toc }





* Full name: `\KWIO\GutenbergBlocks\View\TwigViewExtension`


<details open markdown="block">
  <summary>
    Table of contents
  </summary>
  {: .text-delta }
1. TOC
{:toc}
</details>


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
