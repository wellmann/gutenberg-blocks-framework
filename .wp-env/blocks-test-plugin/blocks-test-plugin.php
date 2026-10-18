<?php

/**
 * Plugin Name: Blocks Test Plugin
 * Description: Dev-only plugin for wp-env. Registers sample blocks for testing.
 */

namespace BlocksTestPlugin;

use KWIO\GutenbergBlocks\Loader;

spl_autoload_register(function (string $class) {
    $prefix = 'KWIO\\GutenbergBlocks\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $file = __DIR__ . '/vendor/kwio/gutenberg-blocks-framework/src/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

new Loader(__FILE__)
    ->loadBlocks('dist/', __NAMESPACE__)
    ->init();