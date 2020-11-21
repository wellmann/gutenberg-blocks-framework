<?php

namespace KWIO\GutenbergBlocksFramework\Tests;

use KWIO\GutenbergBlocksFramework\BlockUtilsTrait;
use PHPUnit\Framework\TestCase;

class BlockUtilsTraitTest extends TestCase
{
    public function testAddClass()
    {
        $traitClassMock = $this->getMockForTrait(BlockUtilsTrait::class);
        $result = $traitClassMock->addClass('test-class');

        $this->assertContains('test-class', $traitClassMock->tagAttr['class']);
    }

    public function testAddData()
    {
        $traitClassMock = $this->getMockForTrait(BlockUtilsTrait::class);
        $result = $traitClassMock->addData('test', ['key' => 'value']);

        $this->assertTrue(array_key_exists('data-test', $traitClassMock->tagAttr));
        $this->assertContains('test-class', $traitClassMock->tagAttr['data-test']);
    }
}