<?php

declare(strict_types=1);

namespace KWIO\GutenbergBlocks\View;

class BladeOne extends \eftec\bladeone\BladeOne
{
    protected BladeOneView $bladeOneView;

    public function setBladeOneView(BladeOneView $bladeOneView): void
    {
        $this->bladeOneView = $bladeOneView;
    }

    protected function compileBem($expression): string
    {
        return $this->phpTagEcho . "\$this->bladeOneView->bem$expression;?>";
    }

    protected function compileRenderBlock($expression): string
    {
        return $this->phpTagEcho . "\$this->bladeOneView->renderBlock$expression;?>";
    }
}