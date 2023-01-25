<?php

namespace KWIO\GutenbergBlocks\Tests;

use KWIO\GutenbergBlocks\View\ViewUtilsTrait;
use ReflectionClass;

class ViewUtilsTraitTest extends TestCase
{
    /**
     * @dataProvider dataProviderForTestBem
     */
    public function testBem(string $element, array $modifier, string $result)
    {
        $traitClassMock = $this->getMockForTrait(ViewUtilsTrait::class);

        $traitClassMockReflection = new ReflectionClass($traitClassMock);

        $traitClassMockReflectionBaseClass = $traitClassMockReflection->getProperty('baseClass');
        $traitClassMockReflectionBaseClass->setAccessible(true);
        $traitClassMockReflectionBaseClass->setValue($traitClassMock, '');

        $traitClassMockAddClass = $traitClassMockReflection->getMethod('bem');
        $traitClassMockAddClass->setAccessible(true);

        $this->assertEquals($result, $traitClassMockAddClass->invokeArgs($traitClassMock, [$element, $modifier]));
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