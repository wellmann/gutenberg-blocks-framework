<?php

namespace KWIO\GutenbergBlocksFramework\Tests;

use KWIO\GutenbergBlocksFramework\BlockUtilsTrait;
use ReflectionClass;

use function Brain\Monkey\Functions\when;

class BlockUtilsTraitTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        when('sanitize_html_class')->returnArg();
    }

    public function testAddClass()
    {
        $traitClassMock = $this->getMockForTrait(BlockUtilsTrait::class);
        $traitClassMock->baseClass = 'base-class';

        $traitClassMockReflection = new ReflectionClass($traitClassMock);
        $traitClassMockAddClass = $traitClassMockReflection->getMethod('addClass');
        $traitClassMockAddClass->setAccessible(true);
        $traitClassMockAddClass->invokeArgs($traitClassMock, ['test-class']);

        $this->assertContains('test-class', $traitClassMock->tagAttr['class']);
    }

    public function testAddData()
    {
        when('esc_attr')->returnArg();

        $traitClassMock = $this->getMockForTrait(BlockUtilsTrait::class);

        $traitClassMockReflection = new ReflectionClass($traitClassMock);
        $traitClassMockAddData = $traitClassMockReflection->getMethod('addData');
        $traitClassMockAddData->setAccessible(true);
        $traitClassMockAddData->invokeArgs($traitClassMock, ['test', ['key' => 'value']]);

        $this->assertTrue(array_key_exists('data-test', $traitClassMock->tagAttr));
        $this->assertEquals($traitClassMock->tagAttr['data-test'], json_encode(['key' => 'value']));
    }
}