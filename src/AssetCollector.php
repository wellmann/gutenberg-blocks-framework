<?php

namespace KWIO\GutenbergBlocksFramework;

class AssetCollector
{
    private PluginConfigDTO $pluginConfig;

    public function __construct(PluginConfigDTO $pluginConfig)
    {
        $this->pluginConfig = $pluginConfig;
    }

    public function enqueueAssets(): void
    {
        wp_enqueue_style(
            $this->pluginConfig->prefix . '-blocks',
            $this->pluginConfig->dirUrl . $this->pluginConfig->distDir . 'blocks.css',
            [],
            $this->shortenVersionHash($this->getVersionHash('blocks.css')),
            is_admin() ? 'all' : 'nonblocking'
        );

        if (!empty($this->getCriticalCss())) {
            wp_add_inline_style($this->pluginConfig->prefix . '-blocks', $this->getCriticalCss());
        }
    }

    public function enqueueEditorAssets(): void
    {
        $manifest = $this->getAssetManifest('editor');

        wp_enqueue_script(
            $this->pluginConfig->prefix . '-blocks-editor',
            $this->pluginConfig->dirUrl . $this->pluginConfig->distDir . 'editor.js',
            $manifest['dependencies'],
            $this->shortenVersionHash($manifest['version']),
            true
        );
        wp_enqueue_style(
            $this->pluginConfig->prefix . '-blocks-editor',
            $this->pluginConfig->dirUrl . $this->pluginConfig->distDir . 'editor.css',
            ['wp-edit-blocks'],
            $this->shortenVersionHash($this->getVersionHash('editor.css'))
        );
    }

    public function enqueueScripts(): void
    {
        $manifest = $this->getAssetManifest('blocks');

        wp_enqueue_script(
            $this->pluginConfig->prefix . '-blocks',
            $this->pluginConfig->dirUrl . $this->pluginConfig->distDir . 'blocks.js',
            $manifest['dependencies'],
            $this->shortenVersionHash($manifest['version']),
            true
        );
    }

    private function getAssetManifest(string $entry): array
    {
        $manifestPath = $this->pluginConfig->dirPath . $this->pluginConfig->distDir . "{$entry}.asset.php";
        if (!file_exists($manifestPath)) {
            return [
                'dependencies' => [],
                'version' => $this->getVersionHash($entry . '.js')
            ];
        }

        return require $manifestPath;
    }

    private function getCriticalCss(): string
    {
        $criticalCssPath = $this->pluginConfig->dirPath . $this->pluginConfig->distDir . 'critical.css';
        if (!is_readable($criticalCssPath)) {
            return '';
        }

        $criticalCss = file_get_contents($criticalCssPath);
        $criticalCss = str_replace('../../../../', content_url('/'), $criticalCss);

        return trim($criticalCss);
    }

    private function getVersionHash(string $asset): string
    {
        $assetPath = $this->pluginConfig->dirPath . $this->pluginConfig->distDir . $asset;
        if (!is_readable($assetPath)) {
            return '';
        }

        return md5(filemtime($assetPath));
    }

    private function shortenVersionHash(string $hash): string
    {
        return substr($hash, 0, 12);
    }
}