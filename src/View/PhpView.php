<?php

namespace KWIO\GutenbergBlocksFramework\View;

class PhpView extends AbstractView
{
    use ViewUtilsTrait;

    public string $defaultView = 'view.php';

    protected function renderWithView(): string
    {
        ob_start();
        extract($this->data, EXTR_SKIP);
        unset($this->data);
        include $this->file;

        return ob_get_clean();
    }
}