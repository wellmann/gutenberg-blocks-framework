<?php

namespace KWIO\GutenbergBlocksFramework\View;

abstract class AbstractView implements ViewInterface
{
    public string $defaultView;

    protected array $data;
    protected string $file;

    public function setData(array $data): ViewInterface
    {
        $this->baseClass = $data['baseClass'];
        $this->renderCount = $data['renderCount'];
        unset($data['baseClass'], $data['renderCount']);

        $this->data = $data;

        return $this;
    }

    public function setFile(string $file): ViewInterface
    {
        $this->file = $file;

        return $this;
    }
}