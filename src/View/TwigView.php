<?php

namespace KWIO\GutenbergBlocksFramework\View;

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\Extension\EscaperExtension;
use Twig\Loader\FilesystemLoader;

class TwigView extends AbstractView
{
    use ViewUtilsTrait;

    public string $defaultView = 'view.twig';

    private array $twigExtensions;

    public function __construct(?AbstractExtension $twigExtension = null)
    {
        $this->twigExtensions = [new TwigViewExtension($this)];

        if (!empty($twigExtension)) {
            $this->twigExtensions[] = $twigExtension;
        }
    }

    protected function renderWithView(): string
    {
        $filePath = $this->locateView($this->file);
        $cacheDir = WP_CONTENT_DIR . '/cache/kwio/gbf/twig';
        $loader = new FilesystemLoader(dirname($filePath));
        $twig = new Environment($loader, [
            'debug' => defined('WP_DEBUG') && WP_DEBUG === true,
            'cache' => $cacheDir
        ]);

        foreach ($this->twigExtensions as $twigExtension) {
            $twig->addExtension($twigExtension);
        }

        $twig->getExtension(EscaperExtension::class)
            ->setEscaper('wp_kses_post', fn(Environment $twig, $string) => wp_kses_post($string));

        $this->data['isEditor'] = $this->isEditor();
        $this->data['post'] = $this->getPost();
        $this->data['renderCount'] = $this->getRenderCount();

        return $twig->render(basename($filePath), $this->data);
    }
}