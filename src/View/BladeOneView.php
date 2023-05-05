<?php

declare(strict_types=1);

namespace KWIO\GutenbergBlocks\View;

class BladeOneView extends AbstractView
{
    use ViewUtilsTrait;

    public static string $defaultView = 'view.blade.php';

    protected function renderWithView(): string
    {
        $filePath = $this->locateView($this->file);
        $cacheDir = $this->cachePath ?? WP_CONTENT_DIR . '/cache/kwio/gbf/bade';
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0775, true);
        }

        $blade = new BladeOne(
            dirname($filePath),
            $cacheDir,
            defined('WP_DEBUG') && WP_DEBUG === true ? BladeOne::MODE_DEBUG : BladeOne::MODE_AUTO
        );
        $blade->setBladeOneView($this);

        $this->data['isEditor'] = $this->isEditor();
        $this->data['post'] = $this->getPost();
        $this->data['renderCount'] = $this->getRenderCount();

        return $blade->run(basename($filePath, '.blade.php'), $this->data);
    }
}