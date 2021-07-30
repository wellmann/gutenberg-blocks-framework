<?php

namespace KWIO\GutenbergBlocksFramework\View;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigView extends AbstractView
{
    use ViewUtilsTrait;

    public string $defaultView = 'view.twig';

    protected function renderWithView(): string
    {
        $cacheDir = WP_CONTENT_DIR . '/cache/kwio/gbf/twig';
        $loader = new FilesystemLoader(dirname($this->file));
        $twig = new Environment($loader, [
            'debug' => defined('WP_DEBUG') && WP_DEBUG === true,
            'cache' => $cacheDir
        ]);
        $twig->addExtension(new TwigViewExtension($this));

        $this->data['isEditor'] = $this->isEditor();

        return $twig->render(basename($this->file), $this->data);
    }
}