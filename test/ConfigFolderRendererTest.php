<?php
/**
 * Created by PhpStorm.
 * User: valentinyelchenko
 * Date: 9/23/18
 * Time: 9:03 PM
 */

namespace ConfigRendererTwigTest;

use ConfigRendererTwig\ConfigFolderRenderer;
use ConfigRendererTwig\RenderingContext;
use ConfigRendererTwig\TemplateRenderer;
use ConfigRendererTwig\Utils;
use PHPUnit\Framework\TestCase;

class ConfigFolderRendererTest extends TestCase
{
    /**
     * @var
     */
    private $templateRenderer;
    /**
     * @var
     */
    private $renderingContext;
    /**
     * @var ConfigFolderRenderer
     */
    private $configRenderer;

    protected function setUp(): void
    {
        $this->templateRenderer = new TemplateRenderer(__DIR__ . '/fixtures/nginx/templates');
        $this->renderingContext = new RenderingContext(
            __DIR__ . '/fixtures/nginx/templates',
            __DIR__ . '/fixtures/nginx/result',
            include __DIR__ . '/fixtures/nginx/vars.php'
        );
        $this->configRenderer = new ConfigFolderRenderer($this->renderingContext, $this->templateRenderer);
        Utils::clearDir(__DIR__ . '/fixtures/nginx/result');
        $this->configRenderer->render();
    }

    /**
     * @test
     */
    public function ensureThatSimpleFileIsCopied()
    {
        $this->assertFileExists(__DIR__ . '/fixtures/nginx/result/koi-utf');
        $this->assertEquals(
            include __DIR__ . '/fixtures/nginx/templates/koi-utf',
            include __DIR__ . '/fixtures/nginx/result/koi-utf'
        );
    }

    /**
     * @test
     */
    public function ensureThatSimpleTemplateIsRendered()
    {
        $this->assertFileExists(__DIR__ . '/fixtures/nginx/result/fastcgi_params');
        $this->assertGreaterThan(0, preg_match(
            '/fastcgi_param\s+REDIRECT_STATUS\s+200;/i',
            file_get_contents(__DIR__ . '/fixtures/nginx/result/fastcgi_params')
        ));
    }

    /**
     * @test
     */
    public function ensureThatEmbeddedTemplatesAreRendered()
    {
        $this->assertDirectoryExists(__DIR__ . '/fixtures/nginx/result/vhosts');
        $this->assertDirectoryExists(__DIR__ . '/fixtures/nginx/result/vhosts/content.stage.motorsport.com');
        $this->assertFileExists(__DIR__ . '/fixtures/nginx/result/vhosts/content.stage.motorsport.com/content.motorsport.com.conf');

        $this->assertGreaterThan(0, preg_match(
            '/listen\s+8081;/',
            file_get_contents(__DIR__ . '/fixtures/nginx/result/vhosts/content.stage.motorsport.com/content.motorsport.com.conf')
        ));
    }
}