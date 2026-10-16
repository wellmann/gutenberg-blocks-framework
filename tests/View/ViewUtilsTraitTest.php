<?php

declare(strict_types=1);

namespace KWIO\GutenbergBlocks\Tests;

use KWIO\GutenbergBlocks\View\ViewUtilsTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionClass;

class ViewUtilsTraitTest extends TestCase
{
    #[DataProvider('dataProviderForTestBem')]
    public function testBem(string $element, array $modifier, bool $jsPrefix, string $result)
    {
        $traitClassMock = new class {
            use ViewUtilsTrait;
        };

        $traitClassMockReflection = new ReflectionClass($traitClassMock);

        $traitClassMockReflectionBaseClass = $traitClassMockReflection->getProperty('baseClass');
        $traitClassMockReflectionBaseClass->setValue($traitClassMock, '');

        $traitClassMockAddClass = $traitClassMockReflection->getMethod('bem');

        $this->assertEquals($result, $traitClassMockAddClass->invokeArgs($traitClassMock, [$element, $modifier, $jsPrefix]));
    }

    public static function dataProviderForTestBem()
    {
        return [
            ['element', [], false, '__element'],
            ['element', ['modifier'], false, '__element __element--modifier'],
            ['element', ['modifier', 'variant'], false, '__element __element--modifier __element--variant'],
            ['element', [], true, '__element js-__element'],
        ];
    }
}