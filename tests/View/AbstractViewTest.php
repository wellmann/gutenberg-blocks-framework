<?php

namespace KWIO\GutenbergBlocksFramework\Tests;

use KWIO\GutenbergBlocksFramework\View\AbstractView;
use ReflectionClass;

use function Brain\Monkey\Functions\when;

class AbstractViewTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        when('wp_is_mobile')->justReturn(false);
        when('esc_attr')->returnArg();

        $this->viewClassData = [
            'baseClass' => 'block-example',
            'renderCount' => 1,
            'wrapperTagName' => 'div',
            'hideMobile' => null,
            'hideDesktop' => null,
            'tagAttr' => ['class' => ['block', 'block-example']]
        ];

        $this->viewClassMock = $this->getMockForAbstractClass(AbstractView::class);
        $this->viewClassMockReflection = new ReflectionClass($this->viewClassMock);

        $viewClassMockReflectionBaseClass = $this->viewClassMockReflection->getProperty('baseClass');
        $viewClassMockReflectionBaseClass->setAccessible(true);
        $viewClassMockReflectionBaseClass->setValue($this->viewClassMock, 'block-example');
    }

    public function testConvertIsStyleToBem()
    {
        $viewClassMockReflectionIsStyleToBem = $this->viewClassMockReflection->getMethod('convertIsStyleToBem');
        $viewClassMockReflectionIsStyleToBem->setAccessible(true);
        $result = $viewClassMockReflectionIsStyleToBem->invokeArgs($this->viewClassMock, [['block', 'block-example', 'is-style-modifier']]);

        $this->assertArrayHasKey('block-example--modifier', array_flip($result));
    }

    public function testBuildTagAttrString()
    {
        when('esc_attr')->returnArg();

        $blockReflectionBuildTagAttrString = $this->viewClassMockReflection->getMethod('buildTagAttrString');
        $blockReflectionBuildTagAttrString->setAccessible(true);
        $result = $blockReflectionBuildTagAttrString->invokeArgs($this->viewClassMock, [[
            'class' => ['block', 'block-example'],
            'id' => ['anchor']
        ]]);

        $this->assertEquals(' class="block block-example" id="anchor"', $result);
    }

    public function testWrapReturnsEmptyStringIfViewFileIsNull()
    {
        $result = $this->viewClassMock
            ->setData(array_merge($this->viewClassData, ['content' => 'lorem ipsum']))
            ->setFile(null)
            ->render();

        $this->assertSame('', $result);
    }

    public function testWrapReturnsEmptyStringIfMobileAndHideMobile()
    {
        when('wp_is_mobile')->justReturn(true);

        $result = $this->viewClassMock
            ->setData(array_merge($this->viewClassData, [
                    'hideMobile' => true,
                    'content' => 'lorem ipsum'
                ]))
            ->setFile('src/example/view.php')
            ->render();

        $this->assertSame('', $result);
    }

    public function testWrapReturnsEmptyStringIfNotMobileAndHideDesktop()
    {
        $result = $this->viewClassMock
            ->setData(array_merge($this->viewClassData, [
                    'hideDesktop' => true,
                    'content' => 'lorem ipsum'
                ]))
            ->setFile('src/example/view.php')
            ->render();

        $this->assertSame('', $result);
    }

    public function testWrapReturnsEmptyWrapperDivIfViewFileIsEmpty()
    {
        $result = $this->viewClassMock
            ->setData($this->viewClassData)
            ->setFile('')
            ->render();

        $this->assertSame('<div class="block block-example"></div>', $result);
    }

    public function testWrapReturnsWrapperDivWithContentIfViewFileIsEmpty()
    {
        $result = $this->viewClassMock
            ->setData(array_merge($this->viewClassData, ['content' => 'lorem ipsum']))
            ->setFile('')
            ->render();

        $this->assertSame('<div class="block block-example">lorem ipsum</div>', $result);
    }

    public function testWrapReturnsCoreBlockWithoutWrapperDivtIfViewFileIsEmpty()
    {
        $result = $this->viewClassMock
            ->setData(array_merge($this->viewClassData, [
                    'baseClass' => 'block-core-example',
                    'content' => 'lorem ipsum'
                ]))
            ->setFile('')
            ->render();

        $this->assertSame('lorem ipsum', $result);
    }

    public function testWrapReturnsWrapperDivWithContent()
    {
        $result = $this->viewClassMock
            ->setData(array_merge($this->viewClassData, ['content' => 'lorem ipsum']))
            ->setFile('src/example/view.php')
            ->render();

        $this->assertSame('<div class="block block-example">lorem ipsum</div>', $result);
    }

    public function testWrapReturnsCoreBlockWithoutWrapperDiv()
    {
        $result = $this->viewClassMock
            ->setData(array_merge($this->viewClassData, [
                    'baseClass' => 'block-core-example',
                    'content' => 'lorem ipsum'
                ]))
            ->setFile('src/example/view.php')
            ->render();

        $this->assertSame('lorem ipsum', $result);
    }
}