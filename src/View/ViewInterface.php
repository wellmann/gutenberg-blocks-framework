<?php

namespace KWIO\GutenbergBlocksFramework\View;

interface ViewInterface
{
    public function render(): string;
    public function setData(array $data): ViewInterface;
    public function setFile(string $file): ViewInterface;
}