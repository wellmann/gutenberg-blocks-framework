---
layout: default
title: TwigView
parent: Reference
has_toc: false
---

# TwigView
{: .no_toc }





* Full name: `\KWIO\GutenbergBlocks\View\TwigView`
* Parent class: `\KWIO\GutenbergBlocks\View\AbstractView`


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
| defaultView | `string` | Holds value of default view file name.  |
| twigExtensions | `array` | Array of `\Twig\Extension\AbstractExtension`.  |

## Methods
### __construct 




```php
TwigView::__construct(?\Twig\Extension\AbstractExtension twigExtension = null)
```


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| twigExtension | `?\Twig\Extension\AbstractExtension` |  |



### renderWithView 
Extract data variables for use in view file.



```php
TwigView::renderWithView(): string
```



**Returns:** `string` Rendered view fil with wrapper elment.
