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
}