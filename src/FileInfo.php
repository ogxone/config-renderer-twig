<?php
/**
 * Created by PhpStorm.
 * User: valentinyelchenko
 * Date: 9/23/18
 * Time: 10:39 PM
 */

namespace ConfigRendererTwig;

/**
 * Class FileInfo
 * @package ConfigRendererTwig\Config
 */
class FileInfo extends \SplFileInfo
{
    /**
     * @var string
     */
    private $basePath;

    /**
     * FileInfo constructor.
     * @param string $basePath
     * @param string $fileName
     */
    public function __construct(string $basePath, string $fileName)
    {
        $this->basePath = $basePath;
        parent::__construct($fileName);
    }

    /**
     * @return string
     */
    public function getRelativePath() : string
    {
        return str_replace($this->basePath, '', $this->getPathname());
    }

    /**
     * @return string
     */
    public function getRelativePathWithTruncatedExt() : string
    {
        return preg_replace('/\.[A-Za-z0-9]+$/', '', $this->getRelativePath());
    }
}