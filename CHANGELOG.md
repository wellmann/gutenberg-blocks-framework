# Changelog


## 2.0.0

* Renaming to follow WordPress terminology

* Switch docs from Jekyll to docsify

* Allow block namespace to be customized through `Loader::setBlockNamespace`

* Update block white list

* Added Blade template engine

* Automatically add color utility classes to block wrapper

* Remove REST url reference on frontend

* Use `add_editor_style` to properly scope editor styles

* Add strict types

* Require block.json for settings

* Change namespace from `GutenbergBlocksFramework` to `GutenbergBlocks`

* Remove deprecated methods and properties


## 1.0.3

* Rename `addCriticalCss` to `addInlineCss`

* Add 3rd parameter to `render_callback`

* Update dependencies


## 1.0.2

* Use default `is-style-` CSS class instead of forcing BEM convention

* Add GitHub workflow

* Add method to configure custom block categories

* Add docs

* Update dependencies

* Allow block view to be overridden in child theme

* Allow blocks to be restricted to specific post types via PHP class or Attribute

* Fix `TemplateCollector` issue

* Rename post type template folder

* Improve unit tests

* Update block white list

* View rendering refactoring

* Output alignwide class when block is align wide.

* Update deprecated block filters for WordPress 5.8

* Fix URL issue when package is used in a theme instead of plugin


## 1.0.1

* Don't render custom wrapper div in overridden core block view


## 1.0.0

* Initial release