<?php

namespace KWIO\GutenbergBlocksFramework;

use Exception;

class BlockCollector
{
    private string $dirPath = '';
    private string $namespace = '';
    private string $prefix = '';
    private array $blocks = [];

    public function setDirPath(string $dirPath)
    {
        $this->dirPath = $dirPath;
    }

    public function setNamespace(string $namespace)
    {
        $this->namespace = $namespace;
    }

    public function setPrefix(string $prefix)
    {
        $this->prefix = $prefix;
    }

    public function getBlocks(): array
    {
        return $this->blocks;
    }

    public function register(string $block): void
    {
        $className = 'BaseBlock';

        // Check if block has a dedicated PHP class.
        $classPath = $this->dirPath . "/{$block}/block.php";
        if (file_exists($classPath)) {
            require_once $classPath;
            $className = str_replace('-', '', ucwords($block, '-'));
        }

        $className = $this->namespace . '\\' . $className;
        $classInstance = new $className($block, $this->dirPath);
        if (!$classInstance instanceof BaseBlock) {
            throw new Exception($className . ' must be an instance of ' . BaseBlock::class);
        }

        $name = sanitize_title($this->prefix) . '/' . $block;
        $args = ['render_callback' => [$classInstance, 'render']];

        if (!empty($classInstance->getAttributes())) {
            $args['attributes'] = $classInstance->getAttributes();
        }

        register_block_type($name, $args);

        $this->blocks[] = $name;
    }
}