<?php

namespace ConfigRendererTwig;

class Utils
{
    /**
     * @param $path
     * @param int $mode
     * @param \array[] ...$options
     * @throws \Exception
     */
    public static function mkdir($path, $mode = 0755, Array ...$options)
    {
        // resolve options
        $forceNew = $options['force_new'] ?? false;
        $recursive = $options['recursive'] ?? true;

        if (is_dir($path)) {
            if ($forceNew) {
                throw new \UnexpectedValueException(sprintf(
                    'Directory `%s` is already exists',
                    $path
                ));
            }
            // directory already exists
            return;
        }
        // validate that path is not a file
        if (is_file($path)) {
            throw new \UnexpectedValueException(sprintf(
                'Include file path `%s` supposed to be a folder, file given',
                $path
            ));
        }
        // try to create dir
        if (false === @mkdir($path, $mode, $recursive)) {
            throw new \Exception(sprintf(
                'Failed to create dir `%s`. Check your permissions',
                $path
            ));
        }
    }

    /**
     * @param $dir
     * @param array $exceptions
     */
    public static function clearDir($dir, Array $exceptions = [])
    {
        if (!is_dir($dir)) {
            throw new \UnexpectedValueException(sprintf(
                'File %s is not a directory', $dir
            ));
        }
        foreach (new \DirectoryIterator($dir) as $fname => $finfo) {
            if ($finfo->isDot()) {
                continue;
            }
            /**@var $finfo \SplFileInfo */
            if ($finfo->isDot() && in_array($fname, $exceptions)) {
                continue;
            }
            if ($finfo->isDir()) {
                self::recursiveRmDir($finfo->getPathname(), $exceptions);
            } else {
                self::unlink($finfo->getPathname());
            }
        }
    }

    /**
     * @param $dir
     * @param array $exceptions
     * @param bool $rmSource
     */
    public static function recursiveRmDir($dir, Array $exceptions = [], $rmSource = false)
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(
                $dir,
                \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($iterator as $filename => $fileInfo) {
            if (in_array($filename, $exceptions)) {
                continue;
            }
            if ($fileInfo->isDir()) {
                self::rmdir($filename);
            } else {
                self::unlink($filename);
            }
        }
        if ($rmSource) {
            self::rmdir($dir);
        }
    }

    /**
     * @param $path
     * @throws \UnexpectedValueException
     */
    public static function unlink($path)
    {
        if (file_exists($path)) {
            if (is_writable($path)) {
                if (!@unlink($path)) {
                    throw new \BadMethodCallException(sprintf(
                        'Failed to delete file `%s`. Check your permission',
                        $path
                    ));
                }
            } else {
                throw new \UnexpectedValueException(sprintf(
                    'File %s is not writable. Please check your permissions', $path
                ));
            }
        } else {
            throw new \UnexpectedValueException(sprintf('File %s is not exists', $path));
        }
    }

    /**
     * @param $path
     * @throws \UnexpectedValueException
     */
    public static function rmdir($path)
    {
        if (file_exists($path)) {
            if (is_writable($path) && is_dir($path)) {
                rmdir($path);
            } else {
                throw new \UnexpectedValueException(sprintf(
                    'Directory %s is not writable or not a dir', $path
                ));
            }
        } else {
            throw new \UnexpectedValueException(sprintf('File %s is not exists', $path));
        }
    }
}