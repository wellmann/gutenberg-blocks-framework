<?php

namespace KWIO\GutenbergBlocksFramework;

class AssetCollector
{
    private const DIST_DIR = 'dist';

    private string $dirPath = '';
    private string $dirUrl = '';
    private string $prefix = '';

    public function __construct(string $dirPath, string $dirUrl, string $prefix)
    {
        $this->dirPath = $dirPath;
        $this->dirUrl = $dirUrl;
        $this->prefix = $prefix;
    }

    public function enqueueAssets(): void
    {
        wp_enqueue_style(
            $this->prefix . '-blocks',
            $this->dirUrl . self::DIST_DIR . '/blocks.css',
            $this->getVersionHash('blocks.css')
        );

        if (!empty($this->getCriticalCss())) {
            wp_add_inline_style($this->prefix . '-blocks', $this->getCriticalCss());
        }
    }

    public function enqueueEditorAssets(): void
    {
        $manifest = $this->getAssetManifest('editor');

        wp_enqueue_script(
            $this->prefix . '-blocks-editor',
            $this->dirUrl . self::DIST_DIR . '/editor.js',
            $manifest['dependencies'],
            $manifest['version'],
            true
        );
        wp_enqueue_style(
            $this->prefix . '-blocks-editor',
            $this->dirUrl . self::DIST_DIR . '/editor.css',
            ['wp-edit-blocks'],
            $this->getVersionHash('editor.css')
        );
    }

    public function enqueueScripts(): void
    {
        $manifest = $this->getAssetManifest('blocks');

        wp_enqueue_script(
            $this->prefix . '-blocks',
            $this->dirUrl . self::DIST_DIR . '/blocks.js',
            $manifest['dependencies'],
            $manifest['version'],
            true
        );
    }

    private function getAssetManifest(string $entry): array
    {
        $manifestPath = $this->dirPath . self::DIST_DIR . "/{$entry}.asset.php";
        if (!file_exists($manifestPath)) {
            return [
                'dependencies' => [],
                'version' => ''
            ];
        }

        return require $manifestPath;
    }

    private function getCriticalCss(): string
    {
        $criticalCssPath = $this->dirPath . self::DIST_DIR . '/critical.css';
        if (!is_readable($criticalCssPath)) {
            return '';
        }

        $criticalCss = file_get_contents($criticalCssPath);
        $criticalCss = str_replace('../../../../', content_url('/'), $criticalCss);

        return trim($criticalCss);
    }

    private function getVersionHash(string $asset): string
    {
        $assetPath = $this->dirPath . self::DIST_DIR . "/{$asset}";
        if (!is_readable($assetPath)) {
            return '';
        }

        return md5(filemtime($assetPath));
    }
}