<?php

namespace KWIO\GutenbergBlocksFramework\View;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TwigViewExtension extends AbstractExtension
{
    private TwigView $twigView;

    public function __construct(TwigView $twigView)
    {
        $this->twigView = $twigView;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('esc_attr', 'esc_attr'),
            new TwigFilter('esc_html', 'esc_html'),
            new TwigFilter('esc_url', 'esc_url'),
            new TwigFilter('wp_kses_post', 'wp_kses_post')
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('bem', [$this->twigView, 'bem']),
            new TwigFunction('post', [$this->twigView, 'getPost']),
            new TwigFunction('render_count', [$this->twigView, 'getRenderCount'])
        ];
    }
}