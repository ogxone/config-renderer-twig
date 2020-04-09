<?php
/**
 * Created by PhpStorm.
 * User: valentinyelchenko
 * Date: 6/30/18
 * Time: 11:37 AM
 */

namespace Ogxone\ConfigRendererTwig;

use Twig\Environment;
use Twig\Loader\ArrayLoader;

/**
 * Class TemplateRenderer
 * @package ConfigRendererTwig\Config
 */
class TemplateRenderer
{
    /**
     * @var \Twig_Environment
     */
    private $fileRenderer;
    /**
     * @var Environment
     */
    private $stringRenderer;
    /**
     * @var ArrayLoader
     */
    private $arrayLoader;

    /**
     * AbstractConfigRenderer constructor.
     * @param string $templateSource
     */
    public function __construct(string $templateSource)
    {
        $this->fileRenderer = new \Twig_Environment(new \Twig_Loader_Filesystem([$templateSource]));
        $this->fileRenderer->addExtension(new \Twig_Extension_StringLoader());

        $this->arrayLoader = new ArrayLoader();
        $this->stringRenderer = new Environment($this->arrayLoader);
    }

    /**
     * @param string $name
     * @param array $vars
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render(string $name, array $vars)
    {
        return $this->fileRenderer->render($name, $vars);
    }

    /**
     * @param string $template
     * @param array $vars
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function renderString(string $template, array $vars): string
    {
        $this->arrayLoader->setTemplate(__FUNCTION__, $template);
        return $this->stringRenderer->render(__FUNCTION__, $vars);
    }

    /**
     * @param string $name
     * @param callable $func
     */
    public function registerFilter(string $name, callable $func)
    {
        $this->fileRenderer->addFilter(new \Twig\TwigFilter($name, $func));
        $this->stringRenderer->addFilter(new \Twig\TwigFilter($name, $func));
    }
}
