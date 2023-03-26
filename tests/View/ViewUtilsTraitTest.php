<?php

declare(strict_types=1);

namespace KWIO\GutenbergBlocks\Tests;

use KWIO\GutenbergBlocks\View\ViewUtilsTrait;
use ReflectionClass;

class ViewUtilsTraitTest extends TestCase
{
    /**
     * @dataProvider dataProviderForTestBem
     */
    public function testBem(string $element, array $modifier, bool $jsPrefix, string $result)
    {
        $traitClassMock = $this->getMockForTrait(ViewUtilsTrait::class);

        $traitClassMockReflection = new ReflectionClass($traitClassMock);

        $traitClassMockReflectionBaseClass = $traitClassMockReflection->getProperty('baseClass');
        $traitClassMockReflectionBaseClass->setAccessible(true);
        $traitClassMockReflectionBaseClass->setValue($traitClassMock, '');

        $traitClassMockAddClass = $traitClassMockReflection->getMethod('bem');
        $traitClassMockAddClass->setAccessible(true);

        $this->assertEquals($result, $traitClassMockAddClass->invokeArgs($traitClassMock, [$element, $modifier, $jsPrefix]));
    }

    public function dataProviderForTestBem()
    {
        return [
            ['element', [], false, '__element'],
            ['element', ['modifier'], false, '__element __element--modifier'],
            ['element', ['modifier', 'variant'], false, '__element __element--modifier __element--variant'],
            ['element', [], true, '__element js-__element'],
        ];
    }
}