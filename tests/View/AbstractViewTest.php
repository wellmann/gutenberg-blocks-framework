<?php

namespace KWIO\GutenbergBlocksFramework\Tests;

use KWIO\GutenbergBlocksFramework\View\AbstractView;

class AbstractViewTest extends TestCase
{
    /**
     * @dataProvider dataProviderForTestBem
     */
    public function testBem(string $element, array $modifier, string $result)
    {
        $viewClassMock = $this->getMockForAbstractClass(AbstractView::class);

        $this->assertEquals($result, $viewClassMock->bem($element, $modifier));
    }

    public function dataProviderForTestBem()
    {
        return [
            ['element', [], '__element'],
            ['element', ['modifier'], '__element __element--modifier'],
            ['element', ['modifier', 'variant'], '__element __element--modifier __element--variant'],
        ];
    }
}