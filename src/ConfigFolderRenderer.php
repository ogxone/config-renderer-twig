<?php
/**
 * Created by PhpStorm.
 * User: valentinyelchenko
 * Date: 9/23/18
 * Time: 8:05 PM
 */

namespace ConfigRendererTwig;

/**
 * Class ConfigFolderRenderer
 * @package ConfigRendererTwig\Config
 */
class ConfigFolderRenderer
{
    /**
     * @var RenderingContext
     */
    private $renderingContext;

    /**
     * @var TemplateRenderer
     */
    private $templateRenderer;

    /**
     * ConfigFolderRenderer constructor.
     * @param RenderingContext $renderingContext
     * @param TemplateRenderer $templateRenderer
     */
    public function __construct(
        RenderingContext $renderingContext,
        TemplateRenderer $templateRenderer
    )
    {
        $this->renderingContext = $renderingContext;
        $this->templateRenderer = $templateRenderer;
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render(): void
    {
        foreach (new \RecursiveIteratorIterator($this->renderingContext->getSource()) as $item) {
            /**@var $item FileInfo */

            if ($item->isFile()) {
                if ($item->getExtension() === 'twig') {
                    $content = $this->templateRenderer->render(
                        $item->getRelativePath(),
                        $this->renderingContext->getVars()
                    );
                    $destFile = $this->renderingContext->getDest()->getPath() . $item->getRelativePathWithTruncatedExt();
                } else {
                    $content = file_get_contents($item->getPathname());
                    $destFile = $this->renderingContext->getDest()->getPath() . $item->getRelativePath();
                }

                $destFile = $this->templateRenderer->renderString($destFile, $this->renderingContext->getVars());

                $this->ensureDestSubDirectoryExists(\dirname($destFile));

                file_put_contents(
                    $destFile,
                    $content
                );
            }
        }
    }

    /**
     * @param string $path
     * @throws \Exception
     */
    private function ensureDestSubDirectoryExists(string $path): void
    {
        Utils::mkdir($path);
    }
}
