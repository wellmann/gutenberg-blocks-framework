<?php

declare(strict_types=1);

namespace KWIO\GutenbergBlocks\Tests;

use KWIO\GutenbergBlocks\Loader;
use ReflectionClass;

use function Brain\Monkey\Functions\when;

class LoaderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        when('plugin_dir_path')->returnArg();
        when('plugin_dir_url')->returnArg();
    }

    public function testconfigHasAttributes()
    {
        $frameworkLoader = new Loader(__FILE__);
        $loaderClassReflection = new ReflectionClass($frameworkLoader);
        $loaderClassconfig = $loaderClassReflection->getProperty('config');

        $pluginCongfig = $loaderClassconfig->getValue($frameworkLoader);

        $this->assertObjectHasProperty('blockWhitelist', $pluginCongfig);
        $this->assertObjectHasProperty('dirPath', $pluginCongfig);
        $this->assertObjectHasProperty('dirUrl', $pluginCongfig);
        $this->assertObjectHasProperty('distDir', $pluginCongfig);
        $this->assertObjectHasProperty('namespace', $pluginCongfig);
        $this->assertObjectHasProperty('viewClass', $pluginCongfig);
    }

    public function testInitHasHooks()
    {
        $frameworkLoader = new Loader(__FILE__);
        $frameworkLoader
            ->loadBlocks('src/', __NAMESPACE__)
            ->init();

        $this->assertNotFalse(has_action('load-post-new.php', '\KWIO\GutenbergBlocks\TemplateCollector->registerTemplates()'));
        $this->assertNotFalse(has_action('init', '\KWIO\GutenbergBlocks\BlockCollector->registerBlocks()'));
        $this->assertNotFalse(has_filter('allowed_block_types_all', '\KWIO\GutenbergBlocks\BlockCollector->filterBlocks()'));
        $this->assertNotFalse(has_filter('block_categories_all'));
    }
}