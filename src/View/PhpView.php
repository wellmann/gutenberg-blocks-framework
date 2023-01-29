<?php

declare(strict_types=1);

namespace KWIO\GutenbergBlocks\View;

class PhpView extends AbstractView
{
    use ViewUtilsTrait;

    public static string $defaultView = 'view.php';

    protected function renderWithView(): string
    {
        ob_start();
        extract($this->data, EXTR_SKIP);
        unset($this->data);
        include $this->locateView($this->file);

        return ob_get_clean();
    }
}