<?php
/**
 * Created by PhpStorm.
 * User: valentinyelchenko
 * Date: 9/23/18
 * Time: 10:38 PM
 */

namespace ConfigRendererTwig;

/**
 * Class SrcDirectoryIterator
 * @package ConfigRendererTwig\Config
 */
class SrcDirectoryIterator extends \RecursiveDirectoryIterator
{
    /**
     * @var
     */
    private static $basePath;

    /**
     * @param string $basePath
     */
    public static function setBasePath(string $basePath): void
    {
        self::$basePath = $basePath;
    }

    /**
     * @return mixed|FileInfo
     */
    public function current()
    {
        return new FileInfo(self::$basePath, parent::current()->getPathname());
    }
}