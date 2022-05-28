<?php

namespace KWIO\GutenbergBlocksFramework\View;

interface ViewInterface
{
    /**
     * Renders view with data.
     *
     * @return string Rendered view.
     */
    public function render(): string;

    /**
     * Adds data to view.
     *
     * @param array $data Data for view.
     *
     * @return ViewInterface
     */
    public function setData(array $data): ViewInterface;

    /**
     * Sets view file to populate with data.
     *
     * @param string $file Absolute path to view file.
     *
     * @return ViewInterface
     */
    public function setFile(string $file): ViewInterface;
}