<?php
/**
 * Created by PhpStorm.
 * User: valentinyelchenko
 * Date: 9/23/18
 * Time: 8:47 PM
 */

namespace Ogxone\ConfigRendererTwig;

/**
 * Class RenderingContext
 * @package ConfigRendererTwig\Config
 */
class RenderingContext
{
    /**
     * @var SrcDirectoryIterator
     */
    private $source;
    /**
     * @var \DirectoryIterator
     */
    private $dest;
    /**
     * @var array
     */
    private $vars;

    /**
     * RenderingContext constructor.
     * @param string $source
     * @param string $dest
     * @param array $vars
     */
    public function __construct(
        string $source,
        string $dest,
        array $vars
    )
    {
        $this->setSource($source);
        $this->setDest($dest);

        $this->vars = $vars;
    }

    /**
     * @param string $source
     */
    private function setSource(string $source) : void
    {
        $this->source = new SrcDirectoryIterator($source);
        SrcDirectoryIterator::setBasePath($source);
    }

    /**
     * @param string $dest
     */
    private function setDest(string $dest) : void
    {
        $this->dest = new \DirectoryIterator($dest);
    }

    /**
     * @return SrcDirectoryIterator
     */
    public function getSource() : SrcDirectoryIterator
    {
        return $this->source;
    }

    /**
     * @return \DirectoryIterator
     */
    public function getDest() : \DirectoryIterator
    {
        return $this->dest;
    }

    /**
     * @return \string
     */
    public function getDestPath() : string
    {
        return $this->dest->getPath();
    }

    /**
     * @return array
     */
    public function getVars() : array
    {
        return $this->vars;
    }
}
