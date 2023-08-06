---
layout: default
title: Config
parent: Reference
has_toc: false
---

# Config
{: .no_toc }

Holds the configured options.



* Full name: `\KWIO\GutenbergBlocks\Config`


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
| blockDir | `string` | Blocks directory relative to the plugin or theme.  |
| blockWhitelist | `array` | Array of blocks that should be whitelisted.  |
| classNamespace | `string` | Namespace of the block classes (`__NAMESPACE__`).  |
| dirPath | `string` | The filesystem directory path for the theme or plugin __FILE__ passed in.  |
| dirUrl | `string` | The URL directory path for the theme or plugin __FILE__ passed in.  |
| distDir | `string` | The path to the block assets dist folder.  |
| isTheme | `bool` | If library is used in plugin or theme.  |
| namespace | `string` | The theme or plugin name.  |
| translationsPath | `string` | The path of the directory of your translation file (e.g. kwio-de_DE.json).  |
| viewCachePath | `string\|null` | Absolute path to a custom cache directory for twig or blade views.  |
| viewClass | `string` | String of a class extending `AbstractView`.  |

