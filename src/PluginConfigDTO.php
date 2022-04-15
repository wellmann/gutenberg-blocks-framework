<?php

namespace KWIO\GutenbergBlocksFramework;

class PluginConfigDTO
{
    public string $blockDir = '';
    public string $blockNamespace = '';
    public array $blockWhitelist = [];
    public string $dirPath = '';
    public string $dirUrl = '';
    public string $distDir = '';
    public string $prefix = '';
    public string $translationsPath = '';
    public string $viewClass;
}