<?php

declare(strict_types=1);

namespace KWIO\GutenbergBlocks\Tests;

use KWIO\GutenbergBlocks\BlockUtilsTrait;
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
        $traitClassMock = $this->createTraitInstance();
        $traitClassMock->baseClass = 'base-class';

        $traitClassMockReflection = new ReflectionClass($traitClassMock);
        $traitClassMockAddClass = $traitClassMockReflection->getMethod('addClass');
        $traitClassMockAddClass->invokeArgs($traitClassMock, ['test-class']);

        $this->assertContains('test-class', $traitClassMock->tagAttr['class']);
    }

    public function testAddData()
    {
        when('esc_attr')->returnArg();

        $traitClassMock = $this->createTraitInstance();

        $traitClassMockReflection = new ReflectionClass($traitClassMock);
        $traitClassMockAddData = $traitClassMockReflection->getMethod('addData');
        $traitClassMockAddData->invokeArgs($traitClassMock, ['test', ['key' => 'value']]);

        $this->assertTrue(array_key_exists('data-test', $traitClassMock->tagAttr));
        $this->assertEquals($traitClassMock->tagAttr['data-test'], json_encode(['key' => 'value']));
    }

    public function testAddJsonData()
    {
        $traitClassMock = $this->createTraitInstance();

        $traitClassMockReflection = new ReflectionClass($traitClassMock);
        $traitClassMockAddData = $traitClassMockReflection->getMethod('addJsonData');
        $traitClassMockAddData->invokeArgs($traitClassMock, ['{}']);

        $this->assertEquals($traitClassMock->data['afterOpeningTag'], '<script type="application/json">{}</script>' . "\n");
    }

    private function createTraitInstance(): object
    {
        return new class {
            use BlockUtilsTrait;

            public string $baseClass = '';
            public array $tagAttr = [];
            public array $data = [];
        };
    }
}