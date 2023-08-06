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
        $loaderClassconfig->setAccessible(true);

        $pluginCongfig = $loaderClassconfig->getValue($frameworkLoader);

        $this->assertObjectHasAttribute('blockWhitelist', $pluginCongfig);
        $this->assertObjectHasAttribute('dirPath', $pluginCongfig);
        $this->assertObjectHasAttribute('dirUrl', $pluginCongfig);
        $this->assertObjectHasAttribute('distDir', $pluginCongfig);
        $this->assertObjectHasAttribute('namespace', $pluginCongfig);
        $this->assertObjectHasAttribute('viewClass', $pluginCongfig);
    }

    /**
     * @dataProvider dataProviderForTestValidBlockNamespace
     */
    public function testValidBlockNamespace(string $file, string $namespace)
    {
        $frameworkLoader = new Loader($file);
        $loaderClassReflection = new ReflectionClass($frameworkLoader);
        $loaderClassconfig = $loaderClassReflection->getProperty('config');
        $loaderClassconfig->setAccessible(true);

        $pluginCongfig = $loaderClassconfig->getValue($frameworkLoader);

        $this->assertEquals($namespace, $pluginCongfig->namespace);
    }

    public function testInitHasHooks()
    {
        $frameworkLoader = new Loader(__FILE__);
        $frameworkLoader
            ->loadBlocks('src/', __NAMESPACE__)
            ->init();

        $this->assertNotFalse(has_action('load-post-new.php', '\KWIO\GutenbergBlocks\TemplateCollector->registerTemplates()'));
        $this->assertNotFalse(has_action('enqueue_block_assets', '\KWIO\GutenbergBlocks\AssetCollector->enqueueAssets()'));
        $this->assertNotFalse(has_action('enqueue_block_editor_assets', '\KWIO\GutenbergBlocks\AssetCollector->enqueueEditorAssets()'));
        $this->assertNotFalse(has_action('init', '\KWIO\GutenbergBlocks\BlockCollector->registerBlocks()'));
        $this->assertNotFalse(has_action('wp_enqueue_scripts', '\KWIO\GutenbergBlocks\AssetCollector->enqueueScripts()'));
        $this->assertNotFalse(has_filter('allowed_block_types_all', '\KWIO\GutenbergBlocks\BlockCollector->filterBlocks()'));
        $this->assertNotFalse(has_filter('block_categories_all'));
        $this->assertNotFalse(has_action('after_setup_theme'));
    }

    public function dataProviderForTestValidBlockNamespace()
    {
        return [
            ['kwio-gutenberg-blocks/bootstrap.php', 'kwio'],
            ['my-long-namespace-gutenberg-blocks/bootstrap.php', 'my-long-namespace'],
            ['namespace-with-gutenberg-gutenberg-blocks/bootstrap.php', 'namespace-with-gutenberg'],
            ['namespace-with-gutenberg-blocks-gutenberg-blocks/bootstrap.php', 'namespace-with-gutenberg-blocks'],
        ];
    }
}