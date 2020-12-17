<?php

namespace KWIO\GutenbergBlocksFramework;

use KWIO\GutenbergBlocksFramework\View\ViewInterface;

class PluginConfigDTO
{
    public string $blockDir = '';
    public string $blockNamespace = '';
    public array $blockWhitelist = [];
    public string $dirPath = '';
    public string $dirUrl = '';
    public string $distDir = '';
    public string $prefix = '';
    public ?ViewInterface $viewClass = null;
}